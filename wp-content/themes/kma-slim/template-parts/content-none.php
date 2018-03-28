<?php

use Includes\Modules\Layouts\Layouts;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = '404';
$subhead = 'Page not found';

include(locate_template('template-parts/sections/top.php'));
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
        <section id="content" class="section support">
            <div class="container">
                <div class="entry-content content">
                    <p>The page you requested does not exist.</p>
                </div>
            </div>
        </section>
    </article>
    <?php include(locate_template('template-parts/sections/social-media.php')); ?>
</div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>