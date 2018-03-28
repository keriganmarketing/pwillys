<?php

use Includes\Modules\Layouts\Layouts;

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
                                <?php the_content(); ?>
                                <p class="title is-5 phone">
                                    <a href="tel:850-588-7947">
                                        <span class="icon">
                                            <i aria-hidden="true" class="fa fa-phone"></i>
                                        </span> 850-588-7947</a></p>
                                <p class="title is-5 hours">
                                    <span class="icon">
                                        <i aria-hidden="true" class="fa fa-clock-o"></i>
                                    </span> 11AM - 9PM</p>
                                <p></p>
                            </div>
                            <div class="column is-12 is-6-desktop">
                                <h2 class="title is-primary is-2 tandelle">Come Visit</h2>
                                <google-map :latitude="30.182326" :longitude="-85.8077277" :zoom="15" name="ww">
                                    <pin :latitude="30.182326" :longitude="-85.8077277" title="The Wicked Wheel">
                                        <p><strong>The Wicked Wheel</strong></p>
                                        <p class="address">10025 Hutchison Blvd,<br>Panama City Beach, FL 32408</p>
                                        <p class="appt-button"><a class="button is-primary is-rounded has-shadow is-fullwidth"
                                                                  href="https://www.google.com/maps/dir/29.9516777,-85.4236099/The+Wicked+Wheel+Bar+%26+Grill,+10025+Hutchison+Blvd,+Panama+City+Beach,+FL+32408">Get
                                                Directions</a></p>
                                    </pin>
                                </google-map>
                            </div>
                            <div class="column is-12 is-6-desktop">
                                <h2 class="title is-primary is-2 tandelle">Get in Touch</h2>
                                <p>Just fill out the form below and we'll get back with you.</p>
                                <?php echo do_shortcode('[contact_form]'); ?>
                            </div>
                        </div>
                    </div>
            </section>
        </article>
        <?php include(locate_template('template-parts/sections/social-media.php')); ?>
    </div>
<?php include(locate_template('template-parts/sections/bot.php')); ?>