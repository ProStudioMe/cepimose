<?php
/**
 * Server-side render for prostudiome/ticker block
 */

// Get the ticker-home category
$category = get_term_by('slug', 'ticker-home', 'category');

if (!$category) {
    echo '<!-- Ticker: Category not found -->';
    return;
}

// Get the specific post
$ticker_post = get_posts([
    'post_type' => 'post',
    'posts_per_page' => 1,
    'category' => $category->term_id,
    'name' => 'ticker-home-do-not-delete',
    'post_status' => 'publish',
]);

if (empty($ticker_post)) {
    echo '<!-- Ticker: Post not found -->';
    return;
}

$ticker_post = $ticker_post[0];
$ticker_items = get_field('ticker_home', $ticker_post->ID);

if (!$ticker_items) {
    echo '<!-- Ticker: No items found -->';
    return;
}

echo '<!-- Ticker: Found ' . count($ticker_items) . ' items -->';
?>

<div class="prostudiome-ticker-bar w-full bg-blue-600 text-white py-2 overflow-hidden">
    <div class="marquee flex items-center whitespace-nowrap">
        <?php foreach ($ticker_items as $item) :
            $title = esc_html($item['ticker_title']);
            $link = $item['ticker_link'];
            $url = $link['url'] ?? '';
            $target = $link['target'] ?? '_self';
            echo '<!-- Ticker: Processing item: ' . $title . ' -->';
        ?>
        <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>"
            class="mx-6 font-semibold hover:underline flex items-center gap-2">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/icon-arrow-right-circle.svg"
                alt="Arrow right circle" class="w-5 h-5">
            <?php echo $title; ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>