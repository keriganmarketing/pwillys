<?php

use Includes\Modules\Events\Events;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$events = new Events();
$event  = $events->getSingleEvent($post->ID);
$terms  = wp_get_post_terms($post->ID, 'bands');
$isBand = (isset($terms) && count($terms) > 0);

$parent = get_post(7);
$parentHeader = $parent->page_information_header_image;

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container is-desktop">
                    <div class="entry-content content">
                        <?php the_content() ?>
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>