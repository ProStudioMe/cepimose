<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ZoloBlocks Pro Preview.
 *
 * @param array $preview .
 */

// filter to add new preview
function zolo_blocks_pro_preview($preview) {
    $preview['datatable'] = trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/preview/datatable.svg';
    $preview['hotspot'] = trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/preview/hotspot.svg';
    $preview['marquee'] = trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/preview/marquee.svg';
	  $preview['postTab']   = trailingslashit( ZOLO_PRO_ADMIN_URL ) . 'assets/preview/marquee.svg';
    $preview['unfold'] = trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/preview/unfold.svg';
    $preview['brandcarousel'] = trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/preview/unfold.svg';

    return $preview;
}
add_filter( 'zolo_blocks_preview', 'zolo_blocks_pro_preview' );
