<?php

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead  = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <?php include(locate_template('template-parts/sections/home-header.php')); ?>
    <?php include(locate_template('template-parts/partials/beach-cams.php')); ?>
    <div class="container" tabindex="0">
        <div class="entry-content content has-text-centered">
            <?php the_content(); ?>
        </div>
    </div>
    
    <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    <?php include(locate_template('template-parts/sections/map.php')); ?>
</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>
