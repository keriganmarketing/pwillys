<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : get_the_archive_title());
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : get_the_archive_description());

$parent = get_post(get_option( 'page_for_posts' ));
$parentHeader = $parent->page_information_header_image;

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
        <section id="content" class="section support">
            <div class="container">
                <div class="entry-content content">
                    <div class="columns is-multiline">
                    <?php

                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/partials/mini-article', get_post_format() );

                        endwhile;

                    ?>
                    </div>
                </div>
            </div>
        </section>
    </article><!-- #post-## -->
</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>