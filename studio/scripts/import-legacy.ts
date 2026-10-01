/**
 * One-time import: copies the categories and articles from the old
 * Insights database (database/quadrant.sqlite) into Sanity.
 * Safe to re-run — documents use fixed IDs and are replaced, not duplicated.
 *
 *   cd studio
 *   npx sanity exec scripts/import-legacy.ts --with-user-token
 */
import {createReadStream} from 'node:fs'
import {basename, resolve} from 'node:path'
import {DatabaseSync} from 'node:sqlite'
import {getCliClient} from 'sanity/cli'

const client = getCliClient({apiVersion: '2025-02-19'})
const root = resolve(process.cwd(), '..')

const categories = [
  {slug: 'off-plan', title: 'Off-Plan'},
  {slug: 'market-insights', title: 'Market Insights'},
  {slug: 'communities', title: 'Communities'},
  {slug: 'buyer-guides', title: 'Buyer Guides'},
  {slug: 'quadrant-view', title: 'Quadrant View'},
]

let keyCounter = 0
const key = () => `k${(keyCounter++).toString(36)}`

const decode = (s: string) =>
  s
    .replace(/<[^>]+>/g, '')
    .replace(/&nbsp;/g, ' ')
    .replace(/&amp;/g, '&')
    .replace(/&quot;/g, '"')
    .replace(/&#0?39;/g, "'")
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .trim()

// The legacy articles only use <p> and <h2>/<h3>, so a simple converter is enough.
function htmlToBlocks(html: string) {
  const blocks = []
  for (const [, tag, inner] of html.matchAll(/<(p|h2|h3|h4|blockquote)[^>]*>([\s\S]*?)<\/\1>/gi)) {
    const text = decode(inner)
    if (!text) continue
    blocks.push({
      _type: 'block',
      _key: key(),
      style: tag.toLowerCase() === 'p' ? 'normal' : tag.toLowerCase(),
      markDefs: [],
      children: [{_type: 'span', _key: key(), text, marks: []}],
    })
  }
  return blocks
}

async function run() {
  for (const [i, c] of categories.entries()) {
    await client.createOrReplace({
      _id: `category-${c.slug}`,
      _type: 'category',
      title: c.title,
      slug: {_type: 'slug', current: c.slug},
      order: i + 1,
    })
  }
  console.log(`✓ ${categories.length} categories`)

  const db = new DatabaseSync(resolve(root, 'database/quadrant.sqlite'), {readOnly: true})
  const rows = db.prepare('SELECT * FROM blog_posts WHERE is_published = 1').all() as any[]

  for (const row of rows) {
    let mainImage
    if (row.main_image) {
      const path = resolve(root, 'public', row.main_image)
      const asset = await client.assets.upload('image', createReadStream(path), {
        filename: basename(path).replace(/\.jfif$/i, '.jpg'),
      })
      mainImage = {_type: 'image', asset: {_type: 'reference', _ref: asset._id}, alt: row.title}
    }

    await client.createOrReplace({
      _id: `legacy-post-${row.id}`,
      _type: 'post',
      title: row.title,
      slug: {_type: 'slug', current: row.slug},
      category: {_type: 'reference', _ref: `category-${row.category}`},
      publishedAt: new Date((row.published_at ?? row.created_at).replace(' ', 'T') + 'Z').toISOString(),
      author: row.author || undefined,
      excerpt: row.excerpt || undefined,
      mainImage,
      body: htmlToBlocks(row.body ?? ''),
      metaTitle: row.meta_title || undefined,
      metaDescription: row.meta_desc || undefined,
    })
    console.log(`✓ ${row.title}`)
  }
}

run().catch((err) => {
  console.error(err)
  process.exit(1)
})
