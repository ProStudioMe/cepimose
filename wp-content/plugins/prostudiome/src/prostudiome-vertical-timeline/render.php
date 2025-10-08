<?php
/**
 * Vertical Timeline Block Render
 * 
 * @param array $attributes Block attributes
 * @param string $content Block content
 * @param WP_Block $block Block instance
 */

if (!isset($attributes['timelineItems']) || !is_array($attributes['timelineItems'])) {
    return;
}

$timeline_items = $attributes['timelineItems'];
$align_class = isset($attributes['align']) ? 'align' . $attributes['align'] : '';

if (empty($timeline_items)) {
    return;
}
?>

<div <?php echo get_block_wrapper_attributes(['class' => 'prostudiome-vertical-timeline ' . $align_class]); ?>>
    <div class="vertical-timeline">
        <?php foreach ($timeline_items as $index => $item): ?>
            <div class="timeline-item" data-index="<?php echo esc_attr($index); ?>">
                <div class="timeline-marker">
                    <div class="timeline-dot"></div>
                </div>
                <div class="timeline-content">
                    <?php if (!empty($item['title'])): ?>
                        <h3 class="timeline-title"><?php echo wp_kses_post($item['title']); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($item['text'])): ?>
                        <div class="timeline-text"><?php echo wpautop(wp_kses_post($item['text'])); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

