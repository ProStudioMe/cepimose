<?php

namespace ZoloPro\Blocks;

/**
 * Loop block
 */
class Loop
{

	/**
	 * Default block attributes
	 *
	 * @var string[]
	 */
	protected $default_block_attributes = [
		'loopLayoutType' => 'grid',
		'gridLayoutType' => 'loop-autogrid',
	];

	/**
	 * Loop block frontend.
	 *
	 * @param array  $attributes .
	 * @param string $content .
	 * @param array  $block .
	 * @return false|string
	 */
	public function render($attributes, $content, $block)
	{
		$attributes = wp_parse_args($attributes, $this->default_block_attributes);
		$loop_context = $block->context['loopContext'] ?? null;
		$query = $loop_context['query'] ?? null;
		$uniq_id = $attributes['uniqueId'] ?? '';
		$layout_type = $attributes['loopLayoutType'] ?? '';
		$grid_layout_type = $attributes['gridLayoutType'] ?? '';
		$parent_classes = $attributes['parentClasses'] ?? [];
		array_push($parent_classes, 'zolo-loop', $uniq_id, $layout_type, $grid_layout_type);
		$wrapper_classes = is_array($parent_classes) ? implode(' ', $parent_classes) : $parent_classes;
		$content = '';
		if (!empty($query) && $query instanceof \WP_Query) {
			while ($query->have_posts()) {
				$query->the_post();

				// Get an instance of the current Post Template block.
				$block_instance = $block->parsed_block;

				// Set the block name to one that does not correspond to an existing registered block.
				// This ensures that for the inner instances of the Post Template block, we do not render any block supports.
				$block_instance['blockName'] = 'core/null';

				$post_id              = get_the_ID();
				$post_type            = get_post_type();
				$filter_block_context = static function ($context) use ($post_id, $post_type) {
					$context['postType'] = $post_type;
					$context['postId']   = $post_id;
					return $context;
				};

				// Use an early priority to so that other 'render_block_context' filters have access to the values.
				add_filter('render_block_context', $filter_block_context, 1);
				// Render the inner blocks of the Post Template block with `dynamic` set to `false` to prevent calling
				// `render_callback` and ensure that no wrapper markup is included.
				$block_content = (new \WP_Block($block_instance))->render(array('dynamic' => false));
				remove_filter('render_block_context', $filter_block_context, 1);

				$content .= $block_content;
			}

			wp_reset_postdata();
		}
		return sprintf(
			'<div %s>%s</div>',
			get_block_wrapper_attributes(['class' => $wrapper_classes]),
			$content
		);
	}
}
