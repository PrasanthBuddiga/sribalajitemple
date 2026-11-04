import type { APIRoute } from 'astro';
import { readFileSync, readdirSync, statSync } from 'fs';
import { join } from 'path';

export const GET: APIRoute = async () => {
  const stotrasDir = join(process.cwd(), 'src/content/stotras');
  
  try {
    const entries = readdirSync(stotrasDir);
    const stotraDirs = entries.filter(entry => {
      const fullPath = join(stotrasDir, entry);
      return statSync(fullPath).isDirectory();
    });
    
    const stotrasList = stotraDirs.map(stotraId => {
      const indexFile = join(stotrasDir, stotraId, 'index.md');
      try {
        const content = readFileSync(indexFile, 'utf-8');
        const frontmatterMatch = content.match(/^---\n([\s\S]*?)\n---/);
        if (frontmatterMatch) {
          const frontmatter = frontmatterMatch[1];
          const titleMatch = frontmatter.match(/title:\s*"([^"]+)"/);
          const descMatch = frontmatter.match(/description:\s*"([^"]+)"/);
          const langMatch = frontmatter.match(/languages:\s*\[([^\]]+)\]/);
          
          return {
            id: stotraId,
            title: titleMatch ? titleMatch[1] : stotraId,
            description: descMatch ? descMatch[1] : '',
            languages: langMatch ? langMatch[1].split(',').map(l => l.trim().replace(/['"]/g, '')) : []
          };
        }
      } catch (e) {
        console.warn(`Could not read index.md for ${stotraId}`);
      }
      
      return {
        id: stotraId,
        title: stotraId,
        description: '',
        languages: []
      };
    });

    return new Response(JSON.stringify(stotrasList), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    console.error('Failed to read stotras directory:', error);
    return new Response(JSON.stringify({ error: 'Failed to load stotras' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

export const prerender = true;