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

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead  = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <?php if($isBand){
                            include(locate_template('template-parts/partials/band-full.php'));
                        }else{
                            include(locate_template('template-parts/partials/event-full.php'));
                        } ?>
                    </div>
                </div>
            </section>
        </article>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>