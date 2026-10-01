<?php

namespace Tests\Unit;

use App\Services\PortableText;
use PHPUnit\Framework\TestCase;

class PortableTextTest extends TestCase
{
    private function block(string $text, array $extra = [], array $marks = [], array $markDefs = []): array
    {
        return array_merge([
            '_type'    => 'block',
            'style'    => 'normal',
            'markDefs' => $markDefs,
            'children' => [['_type' => 'span', 'text' => $text, 'marks' => $marks]],
        ], $extra);
    }

    public function test_renders_styles_and_decorators(): void
    {
        $html = PortableText::render([
            $this->block('Heading', ['style' => 'h2']),
            $this->block('Bold', [], ['strong']),
        ]);

        $this->assertSame('<h2>Heading</h2><p><strong>Bold</strong></p>', $html);
    }

    public function test_escapes_text(): void
    {
        $html = PortableText::render([$this->block('<script>alert(1)</script>')]);

        $this->assertSame('<p>&lt;script&gt;alert(1)&lt;/script&gt;</p>', $html);
    }

    public function test_renders_safe_links_and_drops_unsafe_ones(): void
    {
        $safe = PortableText::render([
            $this->block('site', [], ['l1'], [['_key' => 'l1', '_type' => 'link', 'href' => 'https://quadrant.ae', 'blank' => true]]),
        ]);
        $unsafe = PortableText::render([
            $this->block('x', [], ['l1'], [['_key' => 'l1', '_type' => 'link', 'href' => 'javascript:alert(1)']]),
        ]);

        $this->assertSame('<p><a href="https://quadrant.ae" target="_blank" rel="noopener">site</a></p>', $safe);
        $this->assertSame('<p>x</p>', $unsafe);
    }

    public function test_groups_and_nests_list_items(): void
    {
        $html = PortableText::render([
            $this->block('a', ['listItem' => 'bullet', 'level' => 1]),
            $this->block('a1', ['listItem' => 'number', 'level' => 2]),
            $this->block('b', ['listItem' => 'bullet', 'level' => 1]),
            $this->block('after'),
        ]);

        $this->assertSame('<ul><li>a<ol><li>a1</li></ol></li><li>b</li></ul><p>after</p>', $html);
    }

    public function test_switching_list_type_at_same_level_starts_new_list(): void
    {
        $html = PortableText::render([
            $this->block('a', ['listItem' => 'bullet', 'level' => 1]),
            $this->block('1', ['listItem' => 'number', 'level' => 1]),
        ]);

        $this->assertSame('<ul><li>a</li></ul><ol><li>1</li></ol>', $html);
    }

    public function test_renders_images_and_skips_empty_paragraphs(): void
    {
        $html = PortableText::render([
            $this->block(''),
            ['_type' => 'image', 'url' => 'https://cdn.sanity.io/images/p/d/abc.jpg', 'alt' => 'View', 'caption' => 'Marina'],
        ]);

        $this->assertStringContainsString('<figure><img src="https://cdn.sanity.io/images/p/d/abc.jpg?w=1520', $html);
        $this->assertStringContainsString('alt="View"', $html);
        $this->assertStringContainsString('<figcaption>Marina</figcaption>', $html);
        $this->assertStringNotContainsString('<p>', $html);
    }
}
