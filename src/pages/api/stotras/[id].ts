import type { APIRoute } from 'astro';
import { readFileSync, readdirSync, statSync } from 'fs';
import { join } from 'path';

// Tell Astro which dynamic routes to pre-render at build time using project root
export async function getStaticPaths() {
  const stotrasDir = join(process.cwd(), 'src/content/stotras');
  const entries = readdirSync(stotrasDir);
  const stotraDirs = entries.filter(entry => {
    const fullPath = join(stotrasDir, entry);
    return statSync(fullPath).isDirectory();
  });

  return stotraDirs.map(id => ({
    params: { id },
  }));
}

const languageFileMap: Record<string, string> = {
  sanskrit: 'sanskrit.txt',
  transliteration: 'transliteration.txt',
  telugu: 'telugu.txt',
  kannada: 'kannada.txt',
  tamil: 'tamil.txt'
};

export const GET: APIRoute = async ({ params }) => {
  const { id } = params;
  const stotraDir = join(process.cwd(), 'src/content/stotras', id!);
  
  try {
    const indexFile = join(stotraDir, 'index.md');
    const indexContent = readFileSync(indexFile, 'utf-8');
    
    const frontmatterMatch = indexContent.match(/^---\n([\s\S]*?)\n---/);
    if (!frontmatterMatch) {
      throw new Error('Invalid stotra format');
    }
    
    const frontmatter = frontmatterMatch[1];
    const titleMatch = frontmatter.match(/title:\s*"([^"]+)"/);
    const descMatch = frontmatter.match(/description:\s*"([^"]+)"/);
    const langMatch = frontmatter.match(/languages:\s*\[([^\]]+)\]/);
    
    const languages = langMatch ? langMatch[1].split(',').map(l => l.trim().replace(/['"]/g, '')) : [];
    
    const languageData: Record<string, string> = {};
    for (const lang of languages) {
      const langFile = languageFileMap[lang];
      if (langFile) {
        const langFilePath = join(stotraDir, langFile);
        try {
          languageData[lang] = readFileSync(langFilePath, 'utf-8');
        } catch (e) {
          console.warn(`Could not read ${langFile} for ${id}`);
        }
      }
    }
    
    const data: any = {
      id,
      title: titleMatch ? titleMatch[1] : id,
      description: descMatch ? descMatch[1] : '',
      category: 'Stotras',
      languages: {}
    };
    
    const langNameMap: Record<string, string> = {
      sanskrit: 'Sanskrit',
      transliteration: 'English (IAST)',
      telugu: 'తెలుగు',
      kannada: 'ಕನ್ನಡ',
      tamil: 'தமிழ்'
    };
    
    for (const lang of languages) {
      if (languageData[lang]) {
        data.languages[lang] = {
          name: langNameMap[lang] || lang,
          content: languageData[lang]
        };
      }
    }
    
    return new Response(JSON.stringify(data), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    console.error('Error loading stotra:', error);
    return new Response(JSON.stringify({ error: 'Stotra not found' }), {
      status: 404,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

export const prerender = true;