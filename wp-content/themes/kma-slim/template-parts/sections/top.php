<?php

use Includes\Modules\Social\SocialSettingsPage;
use Includes\Modules\Navwalker\BulmaNavwalker;
use Includes\Modules\Helpers\PageField;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
?>
<a class="skip-link screen-reader-text" href="#mid"><?php _e('Skip to content', 'kmaslim'); ?></a>
<div id="app" class="app" :class="{'modal-open': modalOpen }">
    <div id="MobileNavMenu" class="navbar" :class="{ 'is-active': isOpen }">
        <div class="nav-wrapper">
            <?php wp_nav_menu([
                'theme_location' => 'mobile-menu',
                'container'      => false,
                'menu_class'     => 'navbar-start',
                'fallback_cb'    => '',
                'menu_id'        => 'mobile-menu',
                'link_before'    => '',
                'link_after'     => '',
                'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
                'walker'         => new BulmaNavwalker()
            ]); ?>
            <div class="navbar-close" id="MobileNavClose" data-target="MobileNavMenu" @click="toggleMenu">
                <span class="delete"></span>&nbsp;close menu
            </div>
        </div>
    </div>
    <div class="site-wrapper" :class="{
        'menu-open': isOpen,
        'full-height': footerStuck,
        'scrolling': isScrolling }
    ">
        <div id="top" class="header">
        <div id="alert" style="background-color:#0e5425; text-align: center; color: #FFF; padding:.5rem;">
        <p class="m-0">We are currently closed for the season and will be reopening early 2022.</p>
        </div> 
            <div class="top-one">
                <div class="container">
                    <nav class="navbar navbar-top-row">
                        <div class="navbar-end mini-nav">
                            <div class="navbar-item is-hidden-touch">
                                <a href="/">Home</a>
                            </div>
                            <div class="navbar-item separator is-hidden-touch">
                                |
                            </div>
                            <div class="navbar-item is-hidden-touch">
                                <a href="/contact/">Contact</a>
                            </div>
                            <div class="navbar-item separator is-hidden-touch">
                                |
                            </div>
                            <div class="navbar-item is-hidden-touch">
                                <a href="/history/">History</a>
                            </div>
                            <div class="navbar-item separator is-hidden-touch">
                                |
                            </div>
                            <div class="navbar-item is-hidden-touch">
                                <a href="/faq/">FAQ</a>
                            </div>
                            <div class="navbar-item top-phone">
                                <a href="tel:<?= PageField::getField('contact_info_phone_number', 17); ?>">
                                    <span class="icon" >
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </span> <?= PageField::getField('contact_info_phone_number', 17); ?></a>
                            </div>
                            <div class="navbar-item social-buttons">
                                <div class="burger button is-white is-outlined" id="MobileNavBurger" data-target="MobileNavMenu"
                                     @click="toggleMenu">
                                    <span class="navbar-burger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="top-two">
                <div class="container">
                    <nav class="navbar navbar-bottom-row">
                        <div class="navbar-brand">
                            <a href="/">
                                <img class="logo" src="<?php echo get_template_directory_uri() . '/img/logo.png'; ?>"
                                     alt="<?= bloginfo(); ?>">
                            </a>
                        </div>

                        <div class="navbar-end">
                            <div class="navbar-item is-hidden-mobile">
                                <a class="menu-button button is-primary is-rounded" href="/pineapple-willys-menu/">Our Menu</a>
                            </div>
                            <?php wp_nav_menu([
                                'theme_location' => 'main-menu',
                                'container'      => false,
                                'menu_class'     => 'navbar-start main-navigation-menu is-hidden-desktop-only',
                                'fallback_cb'    => '',
                                'menu_id'        => 'main-menu',
                                'link_before'    => '',
                                'link_after'     => '',
                                'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
                                'walker'         => new BulmaNavwalker()
                            ]); ?>
                            <div class="navbar-item social-buttons is-hidden-touch">
                                <div class="social has-text-left">
                                    <?php
                                    $socialLinks = new SocialSettingsPage();
                                    $socialIcons = $socialLinks->getSocialLinks('svg', 'circle');
                                    if (is_array($socialIcons)) {
                                        foreach ($socialIcons as $socialId => $socialLink) {
                                            echo '<a class="' . $socialId . '" 
                                                    aria-label="follow us on ' . $socialId .'"
                                                    href="' . $socialLink[0] . '" 
                                                    target="_blank" >' . $socialLink[1] . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

