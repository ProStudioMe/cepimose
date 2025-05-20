<?php

namespace ZoloPro\Blocks;

use Zolo\Blocks\PostBlock;
use Zolo\Helpers\ZoloHelpers;
use Zolo\API\GetPostsV1;

/**
 * Post tab frontend rendering main class
 */
class PostTab extends PostBlock {

	/**
	 * Default attributes
	 *
	 * @var array
	 */
	protected $default_block_attributes = [
		'preset'             => 'style-1',
		'postTitleAnimation' => '',
		'showAllCat'         => true,
		'thumbnailSize'      => '',
		'sectionTitle'       => 'Recent Posts',
		'showSectionTitle'   => true,
		'sectionTitleTag'    => 'h2',
		'showSectionTab'     => true,
		'metaSeparator'      => '',
		'postTaxonomy'       => 'category',
		'postCategory'       => [],
		'showReadingTime'    => false,
	];

	/**
	 * Get attributes method.
	 *
	 * @return array
	 */
	public function get_default_attributes() {
		return array_merge( parent::$default_attributes, $this->default_block_attributes );
	}

	/**
	 * Post tab render method for frontend.
	 *
	 * @param array $attributes .
	 * @return false|string
	 */
	public function render( $attributes ) {
		$attributes = wp_parse_args( $attributes, $this->get_default_attributes() );

		$postQuery    = $attributes['postQuery'] ?? [];
		$post_results = apply_filters( 'zolo_post_grid_results', GetPostsV1::zolo_posts_query( $postQuery ) );

		ob_start();
		ZoloHelpers::views(
			'post-tab',
			[
				'settings'     => $attributes,
				'className'    => '',
				'post_results' => $post_results,
				'class_object' => $this,
			]
		);
		return ob_get_clean();
	}
}
