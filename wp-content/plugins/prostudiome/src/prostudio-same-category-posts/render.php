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
            <?php echo esc_html__('This block displays posts from the same category as the current post. It will only work on single post pages.', 'prostudiome'); ?>
        </div>
    </div>
    <?php
    return;
}

// Get current post categories
$categories = get_the_category($current_post_id);

if (empty($categories)) {
    // If no categories, output nothing.
    return;
}

// Get all category IDs from the current post's categories
$category_ids = wp_list_pluck($categories, 'term_id');

// Get block attributes
$number_of_posts = isset($attributes['numberOfPosts']) ? intval($attributes['numberOfPosts']) : 5; // Default changed to 5
$display_featured_image = isset($attributes['displayFeaturedImage']) ? $attributes['displayFeaturedImage'] : true;
$display_excerpt = isset($attributes['displayExcerpt']) ? $attributes['displayExcerpt'] : true;
$display_date = isset($attributes['displayDate']) ? $attributes['displayDate'] : true;
$title = isset($attributes['title']) ? $attributes['title'] : __('Related Posts', 'prostudiome');

// Get posts from the same category, excluding the current post
$related_posts = get_posts([
    'post_type' => 'post',
    'posts_per_page' => $number_of_posts,
    'post__not_in' => [$current_post_id],
    'category__in' => $category_ids,
    'post_status' => 'publish',
]);

if (empty($related_posts)) {
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <div style="padding: 20px; background: #f0f0f0; text-align: center;">
            <?php echo esc_html__('No other posts found in any of the same categories.', 'prostudiome'); ?>
        </div>
    </div>
    <?php
    return;
}

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?> >
    <?php if (!empty($title)) : ?>
        <h3 class=""><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    
   
        <?php foreach ($related_posts as $post) : 
            setup_postdata($post);
            $permalink = get_permalink($post->ID);
            $post_title = get_the_title($post->ID);
        ?>
            <ul >
                <!-- <?php if ($display_featured_image && $has_thumbnail) : ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="same-category-post-image">
                        <?php echo $thumbnail; ?>
                    </a>
                <?php endif; ?> -->
                
                <li >
                    <h4 >
                        <a href="<?php echo esc_url($permalink); ?>">
                            <?php echo esc_html($post_title); ?>
                        </a>
                    </h4>
                    
                  
                    </li>
            </ul>
        <?php endforeach; 
        wp_reset_postdata();
        ?>
  
</div>
