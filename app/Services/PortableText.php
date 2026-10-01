<?php

namespace App\Services;

/**
 * Renders Sanity Portable Text (the Studio's rich-text format) to HTML.
 * Supports the styles, marks and image blocks defined in studio/schemaTypes/post.ts.
 */
class PortableText
{
    private const STYLES = [
        'normal'     => 'p',
        'h2'         => 'h2',
        'h3'         => 'h3',
        'h4'         => 'h4',
        'blockquote' => 'blockquote',
    ];

    private const DECORATORS = [
        'strong'        => 'strong',
        'em'            => 'em',
        'underline'     => 'u',
        'strike-through'=> 's',
        'code'          => 'code',
    ];

    public static function render(?array $blocks): string
    {
        if (!$blocks) {
            return '';
        }

        $html = '';
        $listStack = []; // open list tags, one per nesting level

        foreach ($blocks as $block) {
            $isListItem = ($block['_type'] ?? null) === 'block' && !empty($block['listItem']);

            if ($isListItem) {
                $tag   = $block['listItem'] === 'number' ? 'ol' : 'ul';
                $level = max(1, (int) ($block['level'] ?? 1));

                // Close deeper lists, or switch list type at the same level.
                while (count($listStack) > $level || (count($listStack) === $level && end($listStack) !== $tag)) {
                    $html .= '</li></' . array_pop($listStack) . '>';
                }
                if (count($listStack) === $level) {
                    $html .= '</li>';
                }
                while (count($listStack) < $level) {
                    $html .= '<' . $tag . '>';
                    $listStack[] = $tag;
                }

                $html .= '<li>' . self::children($block);
                continue;
            }

            while ($listStack) {
                $html .= '</li></' . array_pop($listStack) . '>';
            }

            $html .= match ($block['_type'] ?? null) {
                'block' => self::block($block),
                'image' => self::image($block),
                default => '',
            };
        }

        while ($listStack) {
            $html .= '</li></' . array_pop($listStack) . '>';
        }

        return $html;
    }

    private static function block(array $block): string
    {
        $tag   = self::STYLES[$block['style'] ?? 'normal'] ?? 'p';
        $inner = self::children($block);

        // Skip empty paragraphs (writers often leave blank lines).
        if ($tag === 'p' && trim(strip_tags($inner)) === '') {
            return '';
        }

        return "<{$tag}>{$inner}</{$tag}>";
    }

    private static function image(array $block): string
    {
        if (empty($block['url'])) {
            return '';
        }

        $src     = e(Sanity::image($block['url'], 1520));
        $alt     = e($block['alt'] ?? '');
        $caption = !empty($block['caption']) ? '<figcaption>' . e($block['caption']) . '</figcaption>' : '';

        return "<figure><img src=\"{$src}\" alt=\"{$alt}\" loading=\"lazy\">{$caption}</figure>";
    }

    private static function children(array $block): string
    {
        $markDefs = collect($block['markDefs'] ?? [])->keyBy('_key');
        $html = '';

        foreach ($block['children'] ?? [] as $span) {
            $text = nl2br(e($span['text'] ?? ''), false);

            foreach ($span['marks'] ?? [] as $mark) {
                if (isset(self::DECORATORS[$mark])) {
                    $t = self::DECORATORS[$mark];
                    $text = "<{$t}>{$text}</{$t}>";
                } elseif (($def = $markDefs->get($mark)) && ($def['_type'] ?? null) === 'link'
                          && preg_match('~^(https?:|mailto:|tel:|/|#)~i', $def['href'] ?? '')) {
                    $href   = e($def['href']);
                    $target = !empty($def['blank']) ? ' target="_blank" rel="noopener"' : '';
                    $text   = "<a href=\"{$href}\"{$target}>{$text}</a>";
                }
            }

            $html .= $text;
        }

        return $html;
    }
}
