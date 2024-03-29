<?php

use Includes\Modules\Social\SocialSettingsPage;
use Includes\Modules\Helpers\PageField;

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
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>'
        ]); ?>
        <div id="bot">
            <div class="container">
                <div class="columns is-multiline is-justified is-aligned">
                    <div class="column is-6 is-4-desktop footer-contact-column">
                        <img class="footer-logo" tabindex="0" src="<?php echo get_template_directory_uri() . '/img/logo.png'; ?>"
                             alt="Pineapple Willy's">
                        <div class="contact-box" >
                            <p class="footer-phone"><a class="dimbo" href="tel:<?= PageField::getField('contact_info_phone_number', 17); ?>"><?= PageField::getField('contact_info_phone_number', 17); ?></a></p>
                            <p class="open-text" tabindex="0"><?= PageField::getField('contact_info_hours', 17); ?></p>
                            <p class="address" tabindex="0"><?= nl2br(PageField::getField('contact_info_address', 17)); ?></p>
                            <div class="social">
                                <?php
                                $socialLinks = new SocialSettingsPage();
                                $socialIcons = $socialLinks->getSocialLinks('svg', 'circle');
                                if (is_array($socialIcons)) {
                                    foreach ($socialIcons as $socialId => $socialLink) {
                                        echo '<a class="' . $socialId . '"
                                                 href="' . $socialLink[0] . '" 
                                                 aria-label="follow us on ' . $socialId .'"
                                                 target="_blank" >' . $socialLink[1] . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6 is-narrow-desktop has-text-centered"  tabindex="0">
                        <iframe title="Panama City Beach Conditions" 
                            src="https://www.visitpanamacitybeach.com/beach-alerts-iframe/" 
                            class="flag-system-iframe"
                            loading="lazy"
                            width="340"
                            height="250"
                        ></iframe>
                    </div>
                    <div class="column is-6 is-narrow-desktop footer-partners has-text-centered" tabindex="0">
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
    </div><!-- .site-wrapper -->
</div><!-- .app -->
<?php if(!is_front_page()){ ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLEMAPS_KEY; ?>"></script>
<?php } ?>
<?php wp_footer(); ?>