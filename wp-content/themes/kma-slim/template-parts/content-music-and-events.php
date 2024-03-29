<?php

$facebook = new KeriganSolutions\FacebookFeed\WP\FacebookEvent();

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$events = $facebook->query(-1,[
    'order' => 'ASC',
    'orderby' => 'meta_value',
    'meta_key' => 'datestamp',
    'meta_query' => [
        [
            'key'     => 'datestamp',
            'value'   => date('YmdHi'),
            'type'    => 'NUMERIC',
            'compare' => '>='
        ]
    ]
]);

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content">
                        <?php the_content(); ?>

                        <div class="section">
                            <div class="events-grid columns is-multiline">
                                <?php foreach ($events as $event) { ?>
                                    <?php include(locate_template('template-parts/partials/mini-event.php')); ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>