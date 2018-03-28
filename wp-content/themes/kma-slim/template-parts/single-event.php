<?php

use Includes\Modules\Events\Events;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$events      = new Events();
$event = $events->getSingleEvent($post->ID);

$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid" >
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <div class="columns is-multiline">
                            <div class="column is-4" >
                                <p class="title is-4"><?= $event['recurr_readable']; ?></p>
                                <p class="subtitle"><?= $event['time']; ?></p>
                                <p class="location"><a href="https://www.google.com/maps/dir/29.9516777,-85.4236099/The+Wicked+Wheel+Bar+%26+Grill,+10025+Hutchison+Blvd,+Panama+City+Beach,+FL+32408" >
                                    <span class="icon" >
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </span> <?= $event['location']; ?></a></p>
                                <?= $event['content']; ?>
                            </div>
                            <div class="column is-8">
                                <img src="<?= $event['full_image']; ?>" alt="<?= $headline; ?>" >
                            </div>
                        </div>

                        <?php //echo '<pre>',print_r($event),'</pre>'; ?>
                    </div>
                </div>
            </section>
        </article>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>