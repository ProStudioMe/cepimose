<?php
/**
 * Anchor Links Block Template.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 */

// Get block attributes
$title = isset($attributes['title']) ? $attributes['title'] : __('Razdelki na tej strani', 'prostudiome');
$include_headings = isset($attributes['includeHeadings']) ? $attributes['includeHeadings'] : ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
$show_numbers = isset($attributes['showNumbers']) ? $attributes['showNumbers'] : false;

// Get the current post content
$post_content = get_the_content();

// Apply content filters to get the fully processed content (shortcodes, etc.)
$processed_content = apply_filters('the_content', $post_content);

// Create DOMDocument to parse HTML
$dom = new DOMDocument();
// Suppress errors for malformed HTML
libxml_use_internal_errors(true);
// Load HTML with UTF-8 encoding
$success = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $processed_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

// If DOM loading failed, return early
if (!$success) {
    return;
}

// Find all headings with IDs
$xpath = new DOMXPath($dom);
$headings = [];

// Build XPath query for included heading types
$heading_selectors = array_map(function($h) { return "//{$h}[@id]"; }, $include_headings);
$xpath_query = implode(' | ', $heading_selectors);

if (!empty($xpath_query)) {
    $heading_nodes = $xpath->query($xpath_query);
    
    foreach ($heading_nodes as $heading) {
        $id = $heading->getAttribute('id');
        $text = trim($heading->textContent);
        $tag = strtolower($heading->tagName);
        
        if (!empty($id) && !empty($text)) {
            $headings[] = [
                'id' => $id,
                'text' => $text,
                'level' => (int)substr($tag, 1), // h1 -> 1, h2 -> 2, etc.
                'tag' => $tag
            ];
        }
    }
}

// If no headings found, display a message in editor context or return empty
if (empty($headings)) {
    // Check if we're in editor context
    if (is_admin() || (defined('REST_REQUEST') && REST_REQUEST)) {
        ?>
        <div <?php echo get_block_wrapper_attributes(); ?>>
            <div style="padding: 20px; background: #f0f0f0; text-align: center; border: 1px dashed #ccc;">
                <?php echo esc_html__('No headings with IDs found on this page. Add some headings with IDs to see the table of contents.', 'prostudiome'); ?>
            </div>
        </div>
        <?php
    }
    return;
}

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'prostudio-anchor-links'
]);

// Sort headings by their appearance order (they should already be in order from XPath)
?>

<div <?php echo $wrapper_attributes; ?>>

    
    <?php if ($show_numbers) : ?>
        <ol class="anchor-links-list anchor-links-numbered">
    <?php else : ?>
        <ul class="anchor-links-list anchor-links-bullets">
    <?php endif; ?>
    
        <?php foreach ($headings as $heading) : ?>
            <li class="anchor-link-item anchor-link-level-<?php echo esc_attr($heading['level']); ?>">
                <a href="#<?php echo esc_attr($heading['id']); ?>" class="anchor-link">
                    <?php echo esc_html($heading['text']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    
    <?php if ($show_numbers) : ?>
        </ol>
    <?php else : ?>
        </ul>
    <?php endif; ?>
</div>

<script>
// Add smooth scrolling behavior for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const anchorLinks = document.querySelectorAll('.prostudio-anchor-links .anchor-link');
    
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update URL without triggering scroll
                if (history.pushState) {
                    history.pushState(null, null, '#' + targetId);
                }
            }
        });
    });
});
</script> 