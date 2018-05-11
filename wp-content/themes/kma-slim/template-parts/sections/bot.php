<?php

use Includes\Modules\Social\SocialSettingsPage;

?>
    <div class="sticky-footer">
        <?php wp_nav_menu([
            'theme_location' => 'footer-menu',
            'container'      => false,
            'menu_class'     => 'footer-navigation-menu',
            'fallback_cb'    => '',
            'menu_id'        => '',
            'link_before'    => '',
            'link_after'     => '',
            'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>'
        ]); ?>
        <div id="bot">
            <div class="container">
                <div class="columns is-multiline is-justified is-aligned">
                    <div class="column is-6 is-4-desktop">
                        <img class="footer-logo" src="<?php echo get_template_directory_uri() . '/img/logo.png'; ?>"
                             alt="Pineapple Willy's">
                        <div class="contact-box">
                            <p class="footer-phone"><a class="dimbo" href="tel:850-235-0928">850-235-0928</a></p>
                            <p class="open-text">Open 11am daily</p>
                            <p class="address">9875 South Thomas Drive<br>
                                Panama City Beach, Florida 32408</p>
                            <div class="social">
                                <?php
                                $socialLinks = new SocialSettingsPage();
                                $socialIcons = $socialLinks->getSocialLinks('svg', 'circle');
                                if (is_array($socialIcons)) {
                                    foreach ($socialIcons as $socialId => $socialLink) {
                                        echo '<a class="' . $socialId . '" href="' . $socialLink[0] . '" target="_blank" >' . $socialLink[1] . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6 is-4-desktop has-text-centered">
                        <a href="/contact/" class="button is-primary tandelle directions-button is-rounded has-shadow">Get Directions</a>
                        <img class="footer-map" src="<?php echo get_template_directory_uri() . '/img/footer-map.jpg'; ?>"
                             alt="Directions to Pineapple Willy's">
                    </div>
                    <div class="column is-6 is-4-desktop footer-partners has-text-centered">
                        <p class="while-youre-here">While you're in town, visit<br>our sister restaurant:</p>
                        <a href="https://thewickedwheel.com" target="_blank"><img src="<?php echo get_template_directory_uri() . '/img/wicked-wheel-logo.png'; ?>"
                                                                           alt="The Wicked Wheel Restaurant"></a>
                        <p class="visit-website">Visit <a href="https://thewickedwheel.com" target="_blank">website.</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div id="bot-bot">
            <?php include(locate_template('template-parts/partials/copyright.php')); ?>
        </div>
    </div><!-- .sticky-footer -->
    <modal><?= (isset($modalContent) && $modalContent != '' ? $modalContent : ''); ?></modal>
    </div><!-- .site-wrapper -->
</div><!-- .app -->
<?php if(!is_front_page()){ ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRXeRhZCIYcKhtc-rfHCejAJsEW9rYtt4"></script>
<?php } ?>
    <script src="https://www.jscache.com/wejs?wtype=selfserveprop&amp;uniq=244&amp;locationId=550328&amp;lang=en_US&amp;rating=true&amp;nreviews=3&amp;writereviewlink=true&amp;popIdx=true&amp;iswide=true&amp;border=false&amp;display_version=2"></script>
<?php wp_footer(); ?>