<?php
/**
 * Same Category Posts Block Template.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 */

// Get current post ID
$current_post_id = get_the_ID();

// If not a single post, display a message
if (!is_singular('post')) {
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('Ta blok prikazuje objave iz iste kategorije kot trenutna objava. Deluje samo na straneh z eno samo objavo.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

// Get current post categories
$categories = get_the_category($current_post_id);

if (empty($categories)) {
    // If no categories, output nothing.
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('No categories found for this post.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

// Get all category IDs from the current post's categories
$category_ids = wp_list_pluck($categories, 'term_id');

// Get block attributes
$display_featured_image = isset($attributes['displayFeaturedImage']) ? $attributes['displayFeaturedImage'] : true;
$display_excerpt = isset($attributes['displayExcerpt']) ? $attributes['displayExcerpt'] : true;
$display_date = isset($attributes['displayDate']) ? $attributes['displayDate'] : true;
$title = isset($attributes['title']) ? $attributes['title'] : __('Related Posts', 'prostudiome');

// Get posts from the same category, excluding the current post
$related_posts = get_posts([
    'post_type' => 'post',
    'posts_per_page' => -1, // Show all posts
    'post__not_in' => [$current_post_id],
    'category__in' => $category_ids,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes();

// Add JavaScript debugging
?>
<script>
console.log('Same Category Posts Block Debug:');
console.log('Current Post ID:', <?php echo json_encode($current_post_id); ?>);
console.log('Categories found:', <?php echo json_encode(count($categories)); ?>);
console.log('Category IDs:', <?php echo json_encode($category_ids); ?>);
console.log('Related posts found:', <?php echo json_encode(count($related_posts)); ?>);
console.log('Related post IDs:', <?php echo json_encode(array_map(function($post) { return $post->ID; }, $related_posts)); ?>);
</script>

<?php if (empty($related_posts)) : ?>
<div <?php echo $wrapper_attributes; ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('No other posts found in any of the same categories.', 'prostudiome'); ?>
    </div>
</div>
<?php else : ?>
<ul <?php echo $wrapper_attributes; ?>>
    <?php foreach ($related_posts as $post) : 
            setup_postdata($post);
            $permalink = get_permalink($post->ID);
            $post_title = get_the_title($post->ID);
        ?>
    <!-- <?php if ($display_featured_image && $has_thumbnail) : ?>
                <a href="<?php echo esc_url($permalink); ?>" class="same-category-post-image">
                    <?php echo $thumbnail; ?>
                </a>
            <?php endif; ?> -->

    <li>
        <h4>
            <a href="<?php echo esc_url($permalink); ?>">
                <?php echo esc_html($post_title); ?>
            </a>
        </h4>
    </li>
    <?php endforeach; 
        wp_reset_postdata();
        ?>
</ul>
<?php endif; ?>