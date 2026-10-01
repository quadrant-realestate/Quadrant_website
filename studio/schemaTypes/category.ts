import {defineField, defineType} from 'sanity'

export const category = defineType({
  name: 'category',
  title: 'Category',
  type: 'document',
  fields: [
    defineField({name: 'title', title: 'Title', type: 'string', validation: (r) => r.required()}),
    defineField({
      name: 'slug',
      title: 'Slug',
      type: 'slug',
      description: 'Used in the website filter link, e.g. /blogs?category=off-plan',
      options: {source: 'title', maxLength: 64},
      validation: (r) => r.required(),
    }),
    defineField({name: 'order', title: 'Order in filter bar', type: 'number', initialValue: 10}),
  ],
  orderings: [{title: 'Filter order', name: 'order', by: [{field: 'order', direction: 'asc'}]}],
})
