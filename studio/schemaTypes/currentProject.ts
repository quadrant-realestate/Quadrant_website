import {defineField, defineType} from 'sanity'

const STATUS_TITLES: Record<string, string> = {pending: 'Pending', processing: 'Processing'}

export const currentProject = defineType({
  name: 'currentProject',
  title: 'Current project',
  type: 'document',
  fields: [
    defineField({
      name: 'title',
      title: 'Project name',
      type: 'string',
      validation: (r) => r.required().max(120),
    }),
    defineField({
      name: 'status',
      title: 'Status',
      type: 'string',
      description: 'Shown as a badge on the home page and the Buy page.',
      options: {
        list: [
          {title: 'Pending', value: 'pending'},
          {title: 'Processing', value: 'processing'},
        ],
        layout: 'radio',
      },
      initialValue: 'processing',
      validation: (r) => r.required(),
    }),
    defineField({
      name: 'mainImage',
      title: 'Main image',
      type: 'image',
      options: {hotspot: true},
      fields: [
        defineField({
          name: 'alt',
          title: 'Alt text',
          type: 'string',
          description: 'Describe the image for search engines and screen readers.',
        }),
      ],
    }),
    defineField({
      name: 'statusNote',
      title: 'Status note',
      type: 'string',
      description: 'Optional. One short line about where the project stands.',
      validation: (r) => r.max(120),
    }),
    defineField({name: 'community', title: 'Community', type: 'string'}),
    defineField({name: 'developer', title: 'Developer', type: 'string'}),
    defineField({
      name: 'priceFrom',
      title: 'Price from (AED)',
      type: 'number',
      validation: (r) => r.positive(),
    }),
    defineField({
      name: 'bedroomRange',
      title: 'Bedrooms',
      type: 'string',
      description: 'For example: 1-3 BR.',
    }),
    defineField({
      name: 'handover',
      title: 'Handover',
      type: 'string',
      description: 'For example: Q4 2028.',
    }),
    defineField({
      name: 'displayOrder',
      title: 'Display order',
      type: 'number',
      description: 'Optional. Lower numbers show first. The home page shows the first four.',
    }),
  ],
  orderings: [
    {title: 'Display order', name: 'displayOrderAsc', by: [{field: 'displayOrder', direction: 'asc'}]},
  ],
  preview: {
    select: {title: 'title', status: 'status', community: 'community', media: 'mainImage'},
    prepare: ({title, status, community, media}) => ({
      title,
      subtitle: [STATUS_TITLES[status], community].filter(Boolean).join(' · '),
      media,
    }),
  },
})
