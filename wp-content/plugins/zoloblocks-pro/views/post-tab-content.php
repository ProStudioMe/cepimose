<?php

use Zolo\Helpers\ZoloHelpers;

$html          = '';
foreach ($post_results['posts'] as $result) {
	$result = (object) $result;
	$html  .= '<div class="zolo-post-item">';
	$html  .= '<div class="zolo-item-box">';
	$html  .= '<div class="zolo-image-wrapper">';
	$html  .= require ZOLO_DIR . '/views/post-partials/thumbnail.php';
	$html  .= '</div>';
	$html  .= '<div class="zolo-content">';
	$html  .= require ZOLO_DIR . '/views/post-partials/meta/categories.php';

	$html .= require ZOLO_DIR . '/views/post-partials/title.php';
	$html .= '<div class="zolo-meta">';
	$html .= require ZOLO_DIR . '/views/post-partials/meta/author-tab.php';
	$html .= '<span class="zolo-meta-separator">';
	$html .= '</span>';
	if (! empty($settings['showMeta']) && true === $settings['showMeta']) {
		$html .= require ZOLO_DIR . '/views/post-partials/meta/date.php';
		if (! empty($settings['showReadingTime'])) {
			$html .= '<span class="zolo-meta-separator">';
			$html .= '</span>';
			$html .= $metaSeparator;
			$html .= require ZOLO_DIR . '/views/post-partials/meta/reading-time.php';
		}
	}
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
}
?>

<?php
echo wp_kses($html, ZoloHelpers::wp_kses_allowed_svg());
