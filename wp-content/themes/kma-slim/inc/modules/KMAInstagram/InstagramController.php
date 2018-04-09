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
    public $cacheFile;

    public function __construct()
    {
        $this->cacheFile   = wp_normalize_path(dirname(__FILE__) . '/cache/instagram.json');
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
            $request  = $client->request('GET',
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
            $this->saveCacheFile();

            return json_encode($this->requestContent);

        } catch (GuzzleException $e) {
            error_log( 'Error: ' . $e->getMessage(), 0);
            $this->saveEmptyCacheFile();
        }
    }

    public function saveCacheFile()
    {
        echo 'writing file to ' . $this->cacheFile;

        $cacheFile = fopen($this->cacheFile,'w') or die('Unable to open file!');
        fwrite($cacheFile, json_encode($this->requestContent));
        fclose($cacheFile);
    }

    public function saveEmptyCacheFile()
    {
        echo 'saving empty file to ' . $this->cacheFile;

        $cacheFile = fopen($this->cacheFile,'w') or die('Unable to open file!');
        fwrite($cacheFile, '');
        fclose($cacheFile);
    }

    public function getCacheFile()
    {
        $now      = time();
        $fileTime = filectime($this->cacheFile);

        if ( ! file_exists($this->cacheFile)) {
            return false;
        }

        $cacheFilecontent = file_get_contents($this->cacheFile);
        echo $now, ' - ', $fileTime;

        if ($now < $fileTime + 20) {
            echo 'file is good';
            return $cacheFilecontent;
        } else {
            echo 'file is old';
            return false;
        }
    }

    public function getFeed($num = 1)
    {
        $this->num    = $num;
        $savedContent = $this->getCacheFile();
        $content = (!$savedContent ? $this->connectToAPI() : $savedContent);
        $trimmedContent = array_slice(json_decode($content),0 ,$num);

        return json_encode($trimmedContent);
    }

    public function setupAdmin()
    {
        $this->getFeed(20); //set cache file
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
