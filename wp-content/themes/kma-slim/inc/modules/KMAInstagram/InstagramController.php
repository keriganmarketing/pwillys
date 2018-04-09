<?php

namespace Includes\Modules\KMAInstagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class InstagramController
{
    protected $userID;
    protected $accessToken;
    public $num;
    public $requestContent;

    public function __construct()
    {
        $this->userID      = get_option('instagram_page_id');
        $this->accessToken = get_option('instagram_token');
    }

    protected function initialize()
    {

    }

    public function connectToAPI()
    {
        $client = new Client();
        try {
            $request = $client->request('GET',
                'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $this->accessToken);
            $response = json_decode($request->getBody());
            $photos   = [];

            foreach ($response->data as $key => $image) {
                if ($key < $this->num) {
                    $photos[] = [
                        'small'  => $image->images->thumbnail->url,
                        'medium' => $image->images->low_resolution->url,
                        'large'  => $image->images->standard_resolution->url
                    ];
                }
            }

            $this->requestContent = $photos;

            add_action('init', function() {
                //$this->saveCookie();
                $_SESSION['instagram_content'] = json_encode($this->requestContent);
                wp_cache_set( 'instagram_content', json_encode($this->requestContent), 'social_media_content', 3600);
            });

            //echo 'new content fetched';

            return json_encode($this->requestContent);

        }catch (GuzzleException $e) {
            wp_cache_set( 'instagram_content', json_encode([]), 'social_media_content', 600);
            echo '<p>Error: ' . $e->getMessage() . '</p>';
        }
    }

    public function saveCookie()
    {
        $path = parse_url(WP_SITEURL, PHP_URL_PATH);
        $host = parse_url(WP_SITEURL, PHP_URL_HOST);
        $expiry = strtotime('+1 hour');
        setcookie('instagram_content_temp', json_encode($this->requestContent), $expiry, $path, $host);
    }

    public function getFeed($num = 1)
    {
        $this->num = $num;
        $savedContent = wp_cache_get( 'instagram_content', 'social_media_content' );
        if($savedContent === false){
            return $this->connectToAPI();
        }else{
            return $savedContent;
        }
    }

    public function setupAdmin()
    {
        add_action('admin_menu', function () {
            $this->addMenus();
        });
    }

    protected function loadCss()
    {
        wp_enqueue_style('bluma_admin_css', 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css',
            false, '1.0.0');
    }

    public function addMenus()
    {
        add_menu_page("Instagram Settings", "Instagram Settings", "administrator", 'kma-instagram', function () {
            $this->loadCss();
            include(wp_normalize_path(dirname(__FILE__) . '/templates/AdminOverview.php'));
        }, "dashicons-admin-generic");
    }


}
