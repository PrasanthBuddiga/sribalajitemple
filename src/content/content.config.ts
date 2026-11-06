import { defineCollection, z } from 'astro:content';

const stotrasCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    description: z.string(),
    category: z.string().optional(),
    languages: z.array(z.string()).optional(),
  }),
});

export const collections = {
  'stotras': stotrasCollection,
};