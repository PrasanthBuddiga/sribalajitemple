import { defineCollection, z } from 'astro:content';
import { glob } from 'astro/loaders';

const stotras = defineCollection({
  loader: glob({ pattern: '**/index.md', base: './src/content/stotras' }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    category: z.string().optional(),
    languages: z.array(z.string()).optional(),
  }),
});

export const collections = {
  stotras,
};