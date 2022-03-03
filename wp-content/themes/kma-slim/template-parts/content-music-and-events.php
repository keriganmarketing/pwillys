<?php

use Includes\Modules\KMAFacebook\FacebookController;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

$facebook = new FacebookController();
$events = $facebook->getFbEvents(-1,[
    'meta_query' => [
        [
            'key'     => 'datestamp',
            'value'   => date('Ymd'),
            'type'    => 'NUMERIC',
            'compare' => '>='
        ]
    ],
    'orderby' => [
        'datestamp'   => 'ASC',
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