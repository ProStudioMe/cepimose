<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
    <?php esc_html_e( 'Prostudiome – hello from a dynamic block!', 'prostudiome-banner-slider' ); ?>
</p>