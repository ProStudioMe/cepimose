<?php /* Template Name: Archive News */ ?>
<?php get_header(); ?>

<div class="min-h-screen bg-[#f6f8f7] py-10 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Page Title -->
        <h1 class="text-4xl font-semibold text-center mb-10 text-[#1a2e2b]">Novice</h1>

        <?php
    // Query latest post from 'news' category
    $featured_args = array(
      'category_name' => 'news',
      'posts_per_page' => 1,
    );
    $featured_query = new WP_Query($featured_args);
    if ($featured_query->have_posts()):
      while ($featured_query->have_posts()): $featured_query->the_post();
        $featured_id = get_the_ID();
    ?>
        <!-- Latest News Featured Card -->
        <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-md p-6 md:p-8 mb-12 gap-6 items-center">
            <div class="flex-1 flex flex-col justify-between h-full">
                <div class="flex gap-3 mb-3">
                    <span class="bg-[#1a2e2b] text-white text-xs font-semibold px-3 py-1 rounded-full">Zadnji
                        članek</span>
                    <?php
            $cat = get_the_category();
            if ($cat) {
              echo '<span class="bg-[#e6f1ee] text-[#1a2e2b] text-xs font-semibold px-3 py-1 rounded-full">' . esc_html($cat[0]->name) . '</span>';
            }
          ?>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-4 text-[#1a2e2b]">
                    <a href="<?php the_permalink(); ?>" class="hover:underline">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <div class="flex items-center gap-3 mt-auto">
                    <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', 'w-8 h-8 rounded-full border border-[#e6f1ee]'); ?>
                    <span class="text-sm text-[#1a2e2b] font-medium">
                        <?php the_author(); ?>
                    </span>
                    <span class="text-[#7a8f89] text-sm ml-4">
                        <?php echo get_the_date('j. F Y'); ?>
                    </span>
                </div>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
            the_post_thumbnail('large', array('class' => 'rounded-xl w-full h-48 md:h-56 object-cover'));
          } else {
            echo '<div class="rounded-xl w-full h-48 md:h-56 bg-[#e6f1ee]"></div>';
          } ?>
                </a>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>

        <!-- Articles Grid -->
        <h2 class="text-2xl font-semibold mb-6 text-[#1a2e2b]">Članki</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
      // Query all other posts from 'news' category, excluding the featured one
      $args = array(
        'category_name' => 'news',
        'posts_per_page' => -1, // Show all remaining posts
        'post__not_in' => isset($featured_id) ? array($featured_id) : array(),
      );
      $query = new WP_Query($args);
      if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
      ?>
            <div class="bg-white rounded-2xl shadow p-4 flex flex-col">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
            the_post_thumbnail('medium', array('class' => 'rounded-lg mb-4 h-40 object-cover w-full'));
          } else {
            echo '<div class="rounded-lg mb-4 h-40 bg-[#e6f1ee] w-full"></div>';
          } ?>
                </a>
                <?php
          $cat = get_the_category();
          if ($cat) {
            echo '<span class="bg-[#e6f1ee] text-[#1a2e2b] text-xs font-semibold px-2 py-1 rounded mb-2 w-max">' . esc_html($cat[0]->name) . '</span>';
          }
        ?>
                <h3 class="font-bold text-lg mb-2 text-[#1a2e2b]">
                    <a href="<?php the_permalink(); ?>" class="hover:underline">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <div class="flex items-center gap-2 mt-auto">
                    <?php echo get_avatar(get_the_author_meta('ID'), 24, '', '', 'w-6 h-6 rounded-full border border-[#e6f1ee]'); ?>
                    <span class="text-sm text-[#1a2e2b]">
                        <?php the_author(); ?>
                    </span>
                    <span class="text-[#7a8f89] text-xs ml-2">
                        <?php echo get_the_date('j. F Y'); ?>
                    </span>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); else: ?>
            <p class="col-span-3 text-center text-[#7a8f89]">Ni drugih člankov v tej kategoriji.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>