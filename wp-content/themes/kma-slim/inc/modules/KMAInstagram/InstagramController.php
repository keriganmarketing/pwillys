<?php

namespace Includes\Modules\KMAInstagram;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\RequestException;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\WordPressObjectCacheStorage;

class InstagramController
{
    protected $accessToken;
    protected $appId;
    protected $appSecret;
    protected $redirectUri;
    protected $callbackUrl;
    protected $tempToken;
    protected $userID;

    public function __construct()
    {
        $this->userID      = get_option('instagram_page_id');
        $this->accessToken = get_option('instagram_token');
        $this->appId = '2174657569497762';
        $this->appSecret = 'a795ba90bc44482dca37aea223e0fda7';
    }

    public function use()
    {
        $this->setupAdmin();
    }

    public function initCache()
    {
        // Create default HandlerStack
        $stack = HandlerStack::create();

        // Add this middleware to the top with `push`
        $stack->push(new CacheMiddleware(
            new PrivateCacheStrategy(
                new WordPressObjectCacheStorage()
            )
        ), 'cache');

        return $stack;
    }

    public function connectToAPI()
    {
        if(! $this->accessToken){
            return false;
        }

        $client = new Client([ 'handler' => $this->initCache() ]);

        try {

            $response = $client->request('GET',
            'https://graph.facebook.com/v7.0/' . $this->userID . '/media?' . 
            'access_token=' . $this->accessToken . 
            '&fields=media_url,permalink,like_count,comments_count,media_type,thumbnail_url' );

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return false;
            }
        }

        if(isset($response)){
            return $response;
        }else{
            return false;
        }
    }

    public function getFeed($limit = 1)
    {
        if(! $request = $this->connectToAPI()) {
            return json_encode([]);
        }

        $response = json_decode($request->getBody());
        $photos = [];

        $num = 0;

        while($num < $limit){
            $photos[] = $response->data[$num];
            $num++;
        }

        return json_encode($photos);
    }

    public function setupAdmin()
    {
        add_action('admin_menu', function () {
            $this->addMenus();
        });

        add_action( 'rest_api_init', function(){
            $this->addRoutes();
        });
    }

    protected function loadCss()
    {
        wp_enqueue_style('bluma_admin_css', 'https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css',
            false, '1.0.0');
    }

    public function addMenus()
    {
        add_menu_page("Instagram Settings", "Instagram Settings", "administrator", 'kma-instagram', function () {
            $this->loadCss();
            include(wp_normalize_path(dirname(__FILE__) . '/templates/AdminOverview.php'));
        }, "dashicons-admin-generic");
    }

    /**
	 * Add REST API routes
	 */
    public function addRoutes() 
    {
        register_rest_route( 'kerigansolutions/v1', '/instagallerytoken',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'exchangeToken' ],
                'permission_callback'  => '__return_true'
            ]
        );

        register_rest_route( 'kerigansolutions/v1', '/instagramdata',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'getInstagramData' ],
                'permission_callback'  => '__return_true'
            ]
        );
    }

    public function exchangeToken($request)
    {
        $token = $request->get_param( 'token' );
        $client = new Client();

        try {
            $response = $client->request('GET',
            'https://graph.facebook.com/v7.0/oauth/access_token?' . 
            'grant_type=fb_exchange_token&' .
            'client_id=' . $this->appId . '&' .
            'client_secret=' . $this->appSecret . '&' .
            'fb_exchange_token=' . $token );
        } catch (RequestException $e) {
            
        }

        return rest_ensure_response(json_decode($response->getBody()));
    }

    public function getAppAccessToken()
    {
        $client = new Client();

        try {

            $response = $client->request('GET',
                'https://graph.facebook.com/oauth/access_token' .
                '?client_id=' . $this->appId .
                '&client_secret=' . $this->appSecret .
                '&grant_type=client_credentials' . 
                ''
            );

            return json_decode($response->getBody());

        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody(true));
        }
    }

    public function getInstagramData($request)
    {
        $token = $request->get_param( 'token' );
        $userId = $request->get_param( 'user' );
        $appToken = $this->getAppAccessToken()->access_token;
        $client = new Client();

        try {

            $response = $client->request('GET',
                'https://graph.facebook.com/v7.0/' . $userId .
                '?fields=instagram_business_account' .
                '&access_token=' . $token .
                '&app_token=' . $appToken .
                ''
            );

            return rest_ensure_response(json_decode($response->getBody()));

        } catch (RequestException $e) {
            return rest_ensure_response(json_decode($e->getResponse()->getBody(true)));
        }
    }
}
