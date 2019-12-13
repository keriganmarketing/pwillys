<?php

use Includes\Modules\Layouts\Layouts;
use Includes\Modules\Helpers\PageField;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

include(locate_template('template-parts/sections/top.php'));
?>
    <div id="mid">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php include(locate_template('template-parts/sections/support-heading.php')); ?>
            <section id="content" class="section support">
                <div class="container">
                    <div class="entry-content content contact">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                
                                <p class="title is-5 phone">
                                    <a href="tel:<?= PageField::getField('contact_info_phone_number', 17); ?>">
                                        <span class="icon">
                                            <i aria-hidden="true" class="fa fa-phone"></i>
                                        </span> <?= PageField::getField('contact_info_phone_number', 17); ?></a></p>
                                <p class="title is-5 hours">
                                    <span class="icon">
                                        <i aria-hidden="true" class="fa fa-clock-o"></i>
                                    </span> <?= PageField::getField('contact_info_hours', 17); ?></p>
                                <p class="title is-5 email">
                                    <span class="icon">
                                        <i aria-hidden="true" class="fa fa-envelope-o"></i>
                                    </span> Band Booking: <a href="mailto:booking@pwillys.com">booking@pwillys.com</a>
                                </p>
                                <?php the_content(); ?>
                                <p></p>
                            </div>
                            <div class="column is-12 is-6-desktop">
                                <h2 class="title is-primary is-2 dimbo">Come Visit</h2>
                                <google-map :latitude="30.1752009" :longitude="-85.8051388" :zoom="15" name="ww">
                                    <pin :latitude="30.1752009" :longitude="-85.8051388" title="Pineapple Willy's">
                                        <p><strong>Pineapple Willy's</strong></p>
                                        <p class="address"><?= nl2br(PageField::getField('contact_info_address', 17)); ?></p>
                                        <p class="appt-button"><a
                                                    class="button is-primary is-rounded has-shadow is-fullwidth"
                                                    href="https://www.google.com/maps/dir//30.1752009,-85.8051388">
                                                Get Directions</a></p>
                                    </pin>
                                </google-map>
                            </div>
                            <div class="column is-12 is-6-desktop">
                                <h2 class="title is-primary is-2 dimbo">Get in Touch</h2>
                                <p>Just fill out the form below and we'll get back with you.</p>
                                <?php echo do_shortcode('[contact_form]'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>