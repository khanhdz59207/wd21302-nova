<?php

use App\Core\Csrf;

if (!function_exists('e')) {
    /**
     * Escape HTML ngăn chặn XSS (SYS-05)
     */
    function e(?string $value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Lấy CSRF token hiện tại
     */
    function csrf_token(): string {
        return Csrf::getToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Sinh thẻ input hidden chứa CSRF token (SYS-04)
     */
    function csrf_field(): string {
        $token = csrf_token();
        return '<input type="hidden" name="_csrf_token" value="' . e($token) . '">';
    }
}

if (!function_exists('sanitize_product_html')) {
    /**
     * Làm sạch HTML mô tả sản phẩm trước khi render.
     */
    function sanitize_product_html(?string $html): string {
        $html = trim((string)($html ?? ''));
        if ($html === '') {
            return '';
        }

        $allowed = [
            'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'i' => [], 'em' => [],
            'u' => [], 'ul' => [], 'ol' => [], 'li' => [], 'h2' => [], 'h3' => [],
            'h4' => [], 'h5' => [], 'figure' => [], 'figcaption' => [],
            'img' => ['src', 'alt', 'title', 'width', 'height', 'loading', 'decoding'],
            'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [],
            'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
            'a' => ['href', 'title', 'target', 'rel'], 'blockquote' => [], 'span' => [],
        ];
        $dangerous = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'svg', 'math'];

        $previous = libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $loaded = $doc->loadHTML(
            '<?xml encoding="UTF-8"><div id="nova-description-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$loaded) {
            return nl2br(e($html));
        }

        $root = $doc->getElementById('nova-description-root');
        if (!$root) {
            return nl2br(e($html));
        }

        $unwrap = static function (DOMElement $element): void {
            $parent = $element->parentNode;
            if (!$parent) {
                return;
            }
            while ($element->firstChild) {
                $parent->insertBefore($element->firstChild, $element);
            }
            $parent->removeChild($element);
        };

        $walk = static function (DOMNode $node) use (&$walk, $allowed, $dangerous, $unwrap): void {
            foreach (iterator_to_array($node->childNodes) as $child) {
                if (!$child instanceof DOMElement) {
                    continue;
                }

                $tag = strtolower($child->tagName);
                if (in_array($tag, $dangerous, true)) {
                    $node->removeChild($child);
                    continue;
                }

                if (!isset($allowed[$tag])) {
                    $walk($child);
                    $unwrap($child);
                    continue;
                }

                foreach (iterator_to_array($child->attributes) as $attribute) {
                    $name = strtolower($attribute->name);
                    if (!in_array($name, $allowed[$tag], true)) {
                        $child->removeAttributeNode($attribute);
                        continue;
                    }

                    if (in_array($name, ['href', 'src'], true)) {
                        $value = trim($attribute->value);
                        $isDataImage = $tag === 'img' && preg_match('/^data:image\//i', $value);
                        if (!$isDataImage && preg_match('/^\s*(javascript|vbscript|data):/i', $value)) {
                            if ($name === 'src') {
                                $child->removeAttribute('src');
                            } else {
                                $child->setAttribute('href', '#');
                            }
                        }
                    }
                }

                if ($tag === 'img') {
                    if (!$child->hasAttribute('loading')) {
                        $child->setAttribute('loading', 'lazy');
                    }
                    if (!$child->hasAttribute('decoding')) {
                        $child->setAttribute('decoding', 'async');
                    }
                }

                if ($tag === 'a' && strtolower($child->getAttribute('target')) === '_blank') {
                    $child->setAttribute('rel', 'noopener noreferrer');
                }

                $walk($child);
            }
        };

        $walk($root);

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $doc->saveHTML($child);
        }

        return $clean;
    }
}

if (!function_exists('old')) {
    /**
     * Lấy lại giá trị form cũ đã submit khi validate lỗi
     */
    function old(string $key, mixed $default = ''): mixed {
        return $_SESSION['_old_inputs'][$key] ?? $default;
    }
}

