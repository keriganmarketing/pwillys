<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.3
 */

use Includes\Modules\Events\Events;
use Includes\Modules\Reviews\Reviews;
use Includes\Modules\Slider\BulmaSlider;
use Includes\Modules\Helpers\CleanWP;
use Includes\Modules\Layouts\Layouts;
use Includes\Modules\Helpers\Session;
use Includes\Modules\Helpers\PageField;
// use Includes\Modules\Leads\SimpleContact;
use Includes\Modules\Social\SocialSettingsPage;
use Includes\Modules\KMAFacebook\FacebookController;
use Includes\Modules\KMAInstagram\InstagramController;
use Includes\Modules\Forms\Donations;
use Includes\Modules\Forms\Contact;


use Includes\Modules\Menu\Menu;

require('vendor/autoload.php');

new CleanWP();
new Session();

$socialLinks = new SocialSettingsPage();
if (is_admin()) {
    $socialLinks->createPage();
}

$layouts = new Layouts();
$layouts->addPageHeadlines();

// $contact = new SimpleContact();
// $contact->setupAdmin();
// $contact->setupShortcode();

$events = new Events();
$events->setupAdmin();

$events = new BulmaSlider();
$events->setupAdmin();

$ourMenu = new Menu();
$ourMenu->setupAdmin();

$facebook = new FacebookController();
$facebook->use();

$instagram = new InstagramController();
$instagram->setupAdmin();

$reviews = new Reviews();
$reviews->setupAdmin();

$donations = new Donations();
$donations->use();
add_shortcode( 'donations-form', [$donations, 'makeShortcode'] );

$contact = new Contact();
$contact->use();
add_shortcode( 'contact-form', [$contact, 'makeShortcode'] );

PageField::addField('Contact Info',[
	'Phone number' => 'text',
    'Address'  => 'textarea',
    'Hours'  => 'text'
], 17);

add_action('after_setup_theme', function () {
    load_theme_textdomain('kmaslim', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus([
        'mobile-menu'    => esc_html__('Mobile Menu', 'kmaslim'),
        'footer-menu'    => esc_html__('Footer Menu', 'kmaslim'),
        'main-menu'      => esc_html__('Main Navigation', 'kmaslim')
    ]);

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ]);

    
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('styles', get_template_directory_uri() . '/style.css');

    if(is_front_page()){
        wp_enqueue_script('scripts', get_template_directory_uri() . '/front.js', array(), '0.0.1', true);
    }else{
        wp_enqueue_script('scripts', get_template_directory_uri() . '/generic.js', array(), '0.0.1', true);
    }
    
});

function getPageChildren($pageName)
{
    $parent = get_page_by_title($pageName);
    $children = get_pages([
        'parent' => $parent->ID,
        'sort_column'  => 'menu_order',
        'sort_order'   => 'asc'
    ]);

    return $children;
}

function custom_admin_css() {
    echo '<style type="text/css">
    .wp-block { max-width: 800px; }
    </style>';
    }
add_action('admin_head', 'custom_admin_css');

function kma_social_share_function( $atts ){
    $a = shortcode_atts([
        'label' => 'Share This:'
    ], $atts, 'kma_social_share');

    ob_start();
    $label = $a['label'];
    include('template-parts/partials/social-sharing-icons.php');

    return ob_get_clean(); 
}
add_shortcode( 'kma_social_share', 'kma_social_share_function' );