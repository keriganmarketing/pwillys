<?php

namespace Includes\Modules\KMAInstagram;

use GuzzleHttp\Client;

class InstagramController
{
    protected $userID;
    protected $accessToken;
    public $num;

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

        return $client->request('GET',
            'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $this->accessToken);
    }

    public function getFeed($num = 1)
    {
        $this->num = $num;
        $savedContent = (isset($_SESSION['instagram_content']) ? $_SESSION['instagram_content'] : '');
        if (count(json_decode($savedContent)) > 0) {
            return $_SESSION['instagram_content'];
        } else {
            $request  = $this->connectToAPI();
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
            $_SESSION['instagram_content'] = json_encode($photos);

            return json_encode($photos);
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
