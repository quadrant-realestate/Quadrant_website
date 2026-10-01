import {defineConfig} from 'sanity'
import {structureTool} from 'sanity/structure'
import {visionTool} from '@sanity/vision'
import {projectId, dataset} from './env'
import {schemaTypes} from './schemaTypes'

export default defineConfig({
  name: 'default',
  title: 'Quadrant Blogs',
  projectId,
  dataset,
  plugins: [
    structureTool({
      structure: (S) =>
        S.list()
          .title('Content')
          .items([
            S.documentTypeListItem('post').title('Blog posts'),
            S.documentTypeListItem('category').title('Categories'),
          ]),
    }),
    visionTool(),
  ],
  schema: {types: schemaTypes},
})
