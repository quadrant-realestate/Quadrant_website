import {defineArrayMember, defineField, defineType} from 'sanity'

const STATUS_TITLES: Record<string, string> = {
  upcoming: 'New Launch',
  under_construction: 'Under Construction',
  completed: 'Nearing Handover',
}

const PROPERTY_TYPES = ['Apartment', 'Townhouse', 'Villa', 'Penthouse', 'Plot']

const imageWithAlt = (name: string, title: string) =>
  defineField({
    name,
    title,
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
  })

export const offPlanProject = defineType({
  name: 'offPlanProject',
  title: 'Off-plan project',
  type: 'document',
  groups: [
    {name: 'details', title: 'Details', default: true},
    {name: 'media', title: 'Images & files'},
    {name: 'content', title: 'Description'},
    {name: 'location', title: 'Location'},
  ],
  fields: [
    defineField({
      name: 'title',
      title: 'Project name',
      type: 'string',
      group: 'details',
      validation: (r) => r.required().max(120),
    }),
    defineField({
      name: 'slug',
      title: 'URL slug',
      type: 'slug',
      group: 'details',
      description: 'Click "Generate". The project will live at /buy/<slug>.',
      options: {source: 'title', maxLength: 96},
      validation: (r) => r.required(),
    }),
    defineField({
      name: 'isActive',
      title: 'Show on the website',
      type: 'boolean',
      group: 'details',
      description: 'Turn off to hide the project without deleting it.',
      initialValue: true,
    }),
    defineField({
      name: 'status',
      title: 'Status',
      type: 'string',
      group: 'details',
      options: {
        list: Object.entries(STATUS_TITLES).map(([value, title]) => ({title, value})),
        layout: 'radio',
      },
      initialValue: 'upcoming',
      validation: (r) => r.required(),
    }),
    defineField({name: 'developer', title: 'Developer', type: 'string', group: 'details'}),
    defineField({
      name: 'priceFrom',
      title: 'Price from',
      type: 'number',
      group: 'details',
      validation: (r) => r.positive(),
    }),
    defineField({
      name: 'priceCurrency',
      title: 'Currency',
      type: 'string',
      group: 'details',
      initialValue: 'AED',
    }),
    defineField({
      name: 'propertyTypes',
      title: 'Property types',
      type: 'array',
      group: 'details',
      of: [defineArrayMember({type: 'string'})],
      options: {list: PROPERTY_TYPES.map((t) => ({title: t, value: t})), layout: 'grid'},
    }),
    defineField({
      name: 'bedroomRange',
      title: 'Bedrooms',
      type: 'string',
      group: 'details',
      description: 'For example: Studio, 1, 2, 3.',
    }),
    defineField({
      name: 'handoverDate',
      title: 'Handover date',
      type: 'date',
      group: 'details',
      description: 'Shown as a quarter, for example Q4 2028.',
    }),
    defineField({
      name: 'paymentPlan',
      title: 'Payment plan',
      type: 'text',
      rows: 4,
      group: 'details',
      description: 'One milestone per line, for example "20% On Booking". The first line is shown on the card.',
    }),
    defineField({
      name: 'reraPermit',
      title: 'RERA permit',
      type: 'string',
      group: 'details',
    }),
    defineField({
      name: 'isFeatured',
      title: 'Featured',
      type: 'boolean',
      group: 'details',
      description: 'Featured projects come first when visitors sort by "Featured".',
      initialValue: false,
    }),

    {...imageWithAlt('mainImage', 'Main image'), group: 'media', validation: (r) => r.required()},
    defineField({
      name: 'gallery',
      title: 'Gallery',
      type: 'array',
      group: 'media',
      of: [defineArrayMember({type: 'image', options: {hotspot: true}})],
      options: {layout: 'grid'},
    }),
    defineField({
      name: 'floorPlans',
      title: 'Floor plans',
      type: 'array',
      group: 'media',
      of: [
        defineArrayMember({
          type: 'object',
          name: 'floorPlan',
          fields: [
            defineField({
              name: 'unitType',
              title: 'Unit type',
              type: 'string',
              description: 'For example: 1 Bedroom.',
              validation: (r) => r.required(),
            }),
            defineField({name: 'sizeSqft', title: 'Size (sq ft)', type: 'number'}),
            defineField({name: 'image', title: 'Plan image', type: 'image'}),
            defineField({name: 'pdf', title: 'Plan PDF', type: 'file', options: {accept: 'application/pdf'}}),
          ],
          preview: {
            select: {title: 'unitType', size: 'sizeSqft', media: 'image'},
            prepare: ({title, size, media}) => ({title, subtitle: size ? `${size} sq ft` : undefined, media}),
          },
        }),
      ],
    }),
    defineField({
      name: 'brochure',
      title: 'Brochure (PDF)',
      type: 'file',
      group: 'media',
      options: {accept: 'application/pdf'},
    }),

    defineField({
      name: 'shortDescription',
      title: 'Short description',
      type: 'text',
      rows: 3,
      group: 'content',
      description: 'One or two sentences, used for search engines and when there is no full description.',
      validation: (r) => r.max(300),
    }),
    defineField({
      name: 'description',
      title: 'Overview',
      type: 'array',
      group: 'content',
      of: [
        defineArrayMember({
          type: 'block',
          styles: [
            {title: 'Normal', value: 'normal'},
            {title: 'Heading 3', value: 'h3'},
          ],
        }),
      ],
    }),
    defineField({
      name: 'amenities',
      title: 'Highlights & amenities',
      type: 'array',
      group: 'content',
      of: [defineArrayMember({type: 'string'})],
    }),

    defineField({
      name: 'community',
      title: 'Community',
      type: 'string',
      group: 'location',
      description: 'Use the same name as on the website, for example Dubai Marina, so the community filter finds it.',
    }),
    defineField({
      name: 'location',
      title: 'Map location',
      type: 'geopoint',
      group: 'location',
    }),
    defineField({
      name: 'nearbyLandmarks',
      title: 'Nearby landmarks',
      type: 'text',
      rows: 5,
      group: 'location',
      description: 'One per line, for example "Dubai Mall — 10 min".',
    }),
  ],
  orderings: [
    {title: 'Newest first', name: 'createdDesc', by: [{field: '_createdAt', direction: 'desc'}]},
  ],
  preview: {
    select: {title: 'title', status: 'status', community: 'community', active: 'isActive', media: 'mainImage'},
    prepare: ({title, status, community, active, media}) => ({
      title,
      subtitle: [active === false ? 'Hidden' : STATUS_TITLES[status], community].filter(Boolean).join(' · '),
      media,
    }),
  },
})
