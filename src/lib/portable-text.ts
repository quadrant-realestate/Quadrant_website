import { escapeHtml } from "@/lib/format";
import { sanityImage } from "@/lib/sanity";

// Renders Sanity Portable Text (the Studio's rich-text format) to HTML.
// Supports the styles, marks and image blocks defined in the Studio's post schema.

type Span = { _type: string; text?: string; marks?: string[] };
type MarkDef = { _key: string; _type: string; href?: string; blank?: boolean };
type Block = {
  _type: string;
  style?: string;
  listItem?: "bullet" | "number";
  level?: number;
  children?: Span[];
  markDefs?: MarkDef[];
  url?: string;
  alt?: string;
  caption?: string;
};

const STYLES: Record<string, string> = { normal: "p", h2: "h2", h3: "h3", h4: "h4", blockquote: "blockquote" };
const DECORATORS: Record<string, string> = {
  strong: "strong",
  em: "em",
  underline: "u",
  "strike-through": "s",
  code: "code",
};

export function renderPortableText(blocks: Block[] | null | undefined): string {
  if (!blocks?.length) return "";

  let html = "";
  const listStack: string[] = []; // open list tags, one per nesting level

  for (const block of blocks) {
    if (block._type === "block" && block.listItem) {
      const tag = block.listItem === "number" ? "ol" : "ul";
      const level = Math.max(1, Math.floor(block.level ?? 1));

      // Close deeper lists, or switch list type at the same level.
      while (listStack.length > level || (listStack.length === level && listStack[listStack.length - 1] !== tag)) {
        html += `</li></${listStack.pop()}>`;
      }
      if (listStack.length === level) html += "</li>";
      while (listStack.length < level) {
        html += `<${tag}>`;
        listStack.push(tag);
      }
      html += `<li>${children(block)}`;
      continue;
    }

    while (listStack.length) html += `</li></${listStack.pop()}>`;

    if (block._type === "block") html += renderBlock(block);
    else if (block._type === "image") html += renderImage(block);
  }

  while (listStack.length) html += `</li></${listStack.pop()}>`;
  return html;
}

function renderBlock(block: Block): string {
  const tag = STYLES[block.style ?? "normal"] ?? "p";
  const inner = children(block);
  // Skip empty paragraphs (writers often leave blank lines).
  if (tag === "p" && inner.replace(/<[^>]*>/g, "").trim() === "") return "";
  return `<${tag}>${inner}</${tag}>`;
}

function renderImage(block: Block): string {
  if (!block.url) return "";
  const src = escapeHtml(sanityImage(block.url, 1520) ?? "");
  const alt = escapeHtml(block.alt ?? "");
  const caption = block.caption ? `<figcaption>${escapeHtml(block.caption)}</figcaption>` : "";
  return `<figure><img src="${src}" alt="${alt}" loading="lazy">${caption}</figure>`;
}

function children(block: Block): string {
  const defs = new Map((block.markDefs ?? []).map((d) => [d._key, d]));
  let html = "";

  for (const span of block.children ?? []) {
    let text = escapeHtml(span.text ?? "").replace(/\r?\n/g, "<br>\n");

    for (const mark of span.marks ?? []) {
      const decorator = DECORATORS[mark];
      const def = defs.get(mark);
      if (decorator) {
        text = `<${decorator}>${text}</${decorator}>`;
      } else if (def?._type === "link" && /^(https?:|mailto:|tel:|\/|#)/i.test(def.href ?? "")) {
        const target = def.blank ? ' target="_blank" rel="noopener"' : "";
        text = `<a href="${escapeHtml(def.href ?? "")}"${target}>${text}</a>`;
      }
    }

    html += text;
  }

  return html;
}
