<?php

$post = get_posts(['posts_per_page' => 1]);

?>
<div class="card social-module facebook has-text-centered <?= ($hasImage == true ? 'has-image' : 'no-image'); ?>">
    <div class="card-image">
        <figure class="image is-16by3">
        <a href="<?php echo get_the_permalink($post[0]->ID); ?>">
            <img src="<?php echo get_the_post_thumbnail_url($post[0]->ID); ?>">
        </a>
        </figure>
    </div>
    <div class="card-content">
        <h2 class="title is-3 dimbo is-primary"><?php echo $post[0]->post_title; ?></h2>
        Posted <?php echo get_the_date(get_option( 'date_format' ), $post[0]->ID); ?>
        <!-- <?php echo apply_filters('the_content', wp_trim_words($post[0]->post_content, 20, '...')); ?> -->
    </div>
    <div class="card-footer">
        <a class="card-footer-item" href="<?php echo get_the_permalink($post[0]->ID); ?>">Read More</a>
        <a class="card-footer-item" href="/pineapple-culture/">More Culture</a>
    </div>
</div>