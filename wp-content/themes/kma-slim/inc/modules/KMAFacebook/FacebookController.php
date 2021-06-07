<?php

namespace Includes\Modules\KMAFacebook;

use KeriganSolutions\FacebookFeed\FacebookFeed;
use KeriganSolutions\FacebookFeed\FacebookReviews;
use KeriganSolutions\FacebookFeed\FacebookPhotoGallery;
use KeriganSolutions\FacebookFeed\FacebookEvents;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\RequestException;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\WordPressObjectCacheStorage;

class FacebookController
{
    protected $facebookPageID;
    protected $facebookToken;
    protected $appId;
    protected $appSecret;

    public function __construct()
    {
        $this->facebookPageID = get_option('facebook_page_id');
        $this->facebookToken = get_option('facebook_token');
        $this->appSecret = get_option('facebook_app_secret');
        $this->appId = '353903931781568';
        // $this->syncEvents();
    }

    // CACHE SYSTEM

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

    // GETTING THINGS FROM FB

    // TODO public function getReviews($num = 1)
    // {
    //     $feed = new FacebookReviews($this->facebookPageID,$this->facebookToken);
    //     return $feed->fetch($num);
    // }

    public function getEvents($limit = null, $before = null, $after = null)
    {
        $feed = new FacebookEvents($this->facebookPageID,$this->facebookToken);
        return $feed->fetch($limit, $before, $after);
    }

    public function getPhotos($albumId, $limit = null, $before = null, $after = null)
    {
        $feed =  new FacebookPhotoGallery($this->facebookPageID,$this->facebookToken);
        return $feed->albumPhotos($albumId, $limit, $before, $after);
    }

    public function getAlbums($albumId, $limit = null, $before = null, $after = null)
    {
        $feed =  new FacebookPhotoGallery($this->facebookPageID,$this->facebookToken);
        return $feed->albums($albumId, $limit, $before, $after);
    }

    public function getFeed($num = 1)
    {
        try{
            $feed = new FacebookFeed($this->facebookPageID,$this->facebookToken);
            $response = $feed->fetch($num);
        }catch(RequestException $e){
            $response = $e->getResponse()->getBody(true);
        }

        // echo '<pre>',print_r($response),'</pre>';

        return $response;
    }

    // GETTING THINGS FROM WP

    public function getFbPosts($num = -1, $args = [])
    {
        $request = [
            'posts_per_page' => $num,
            'offset'         => 0,
            'order'          => 'DESC',
            'orderby'        => 'date_posted',
            'post_type'      => 'kma-fb-post',
            'post_status'    => 'publish',
        ];

        $request   = array_merge($request, $args);
        $postArray = get_posts($request);

        $output = [];
        foreach($postArray as $post){
            $post->post_link = get_post_meta($post->ID, 'post_link', true);
            $post->full_image_url = get_post_meta($post->ID, 'full_image_url', true);
            $post->attachments = get_post_meta($post->ID, 'attachments', true);
            $post->type = get_post_meta($post->ID, 'type', true);
            $post->status_type = get_post_meta($post->ID, 'status_type', true);
            $post->target_url = get_post_meta($post->ID, 'target_url', true);
            $post->image_src = get_post_meta($post->ID, 'image_src', true);
            $post->description = get_post_meta($post->ID, 'description', true);
            $post->media_type = get_post_meta($post->ID, 'media_type', true);
            $post->title = get_post_meta($post->ID, 'title', true);
            $post->url = get_post_meta($post->ID, 'url', true);
            $post->unshimmed_url = get_post_meta($post->ID, 'unshimmed_url', true);

            $output[] = $post;
        }

        return $output;
    }

    public function getFbEvents($num = -1, $args = [])
    {
        $request = [
            'posts_per_page' => $num,
            'offset'         => 0,
            'order'          => 'DESC',
            'orderby'        => 'date_posted',
            'post_type'      => 'kma-fb-event',
            'post_status'    => 'publish',
        ];

        $request   = array_merge($request, $args);
        $postArray = get_posts($request);

        $output = [];
        foreach($postArray as $post){
            $post->event_name = get_post_meta($post->ID, 'event_name', true);
            $post->post_link = get_post_meta($post->ID, 'post_link', true);
            $post->where = get_post_meta($post->ID, 'where', false);
            $post->full_image_url = get_post_meta($post->ID, 'full_image_url', true);
            $post->start_time = get_post_meta($post->ID, 'start_time', true);
            $post->end_time = get_post_meta($post->ID, 'end_time', true);
            $output[] = $post;
        }

        return $output;
    }

    // KEEP WP UPDATED WITH FB

    public function cron()
    {
        if(! wp_next_scheduled('kma-fb-sync')){
            wp_schedule_event(strtotime('01:00:00'), 'hourly', 'kma-fb-sync');
        }

        add_action('kma-fb-sync', [$this,'updatePosts']);
    }

    public function syncPosts()
    {
        $this->updatePosts(30) ? wp_send_json_success() : wp_send_json_error();
    }

    public function syncEvents()
    {
        $this->updateEvents(30) ? wp_send_json_success() : wp_send_json_error();
    }


    public function updateEvents($n = 30)
    {
        $feed = $this->getEvents($n);
        // echo '<pre>',print_r($feed),'</pre>';

        if($feed->posts){

            foreach($feed->posts as $fbpost){
                $postArray = [
                    'ID' => 0,
                    'post_content' => $fbpost->description,
                    'post_title' => $fbpost->id,
                    'post_status' => 'publish',
                    'post_type' => 'kma-fb-event',
                    'meta_input' => [
                        'event_name' => $fbpost->name,
                        'post_link' => 'https://www.facebook.com/events/' . $fbpost->permalink_url,
                        'where' => $fbpost->place->name,
                        'full_image_url' => $fbpost->cover->source,
                        'start_time' => $fbpost->start_time,
                        'end_time' => $fbpost->end_time,
                    ]
                ];

                // echo '<pre>',print_r($postArray),'</pre>';
                $postExists = get_page_by_title($fbpost->id, OBJECT, 'kma-fb-event');

                if(isset($postExists->ID)){
                    $postArray['ID'] = $postExists->ID;
                    wp_update_post($postArray);
                }else{
                    wp_insert_post($postArray);
                }

            }
            return true;
        }
        return false;
    }

    public function updatePosts($n = 30)
    {
        $feed = $this->getFeed($n);

        if($feed->posts){

            foreach($feed->posts as $fbpost){
                if($fbpost->status_type == 'mobile_status_update' && $fbpost->message == ''){
                    //nothing to do here...
                }else{

                    $postArray = [
                        'ID' => 0,
                        'post_date' => date("Y-m-d H:i:s", strtotime($fbpost->created_time)),
                        'post_content' => $fbpost->message,
                        'post_title' => $fbpost->id,
                        'post_status' => 'publish',
                        'post_type' => 'kma-fb-post',
                        'meta_input' => [
                            'post_link' => isset($fbpost->permalink_url) ? $fbpost->permalink_url : '',
                            'full_image_url' => isset($fbpost->full_picture) ? $fbpost->full_picture : '',
                            'status_type' => isset($fbpost->status_type) ? $fbpost->status_type : '',
                        ]
                    ];

                    if(isset($fbpost->attachments)){
                        $media = $fbpost->attachments->data[0];
                        $postArray['meta_input']['attachments'] = $fbpost->attachments->data;
                        $postArray['meta_input']['target_url'] = isset($media->target->url ) ? $media->target->url : '';
                        $postArray['meta_input']['image_src'] = isset($media->media->image->src ) ? $media->media->image->src : '';
                        $postArray['meta_input']['description'] = isset($media->description ) ? $media->description : '';
                        $postArray['meta_input']['media_type'] = isset($media->media_type ) ? $media->media_type : '';
                        $postArray['meta_input']['type'] = isset($media->type ) ? $media->type : '';
                        $postArray['meta_input']['title'] = isset($media->title ) ? $media->title : '';
                        $postArray['meta_input']['url'] = $media->url;
                        $postArray['meta_input']['unshimmed_url'] = $media->unshimmed_url;
                    }

                    $postExists = get_page_by_title($fbpost->id, OBJECT, 'kma-fb-post');

                    if(isset($postExists->ID)){
                        $postArray['ID'] = $postExists->ID;
                        wp_update_post($postArray);
                        // update_post_meta($postExists->ID, 'attachments', $fbpost->media);
                        
                    }else{
                        $postID = wp_insert_post($postArray);
                        // update_post_meta($postID, 'attachments', $fbpost->media);
                    }
                }

            }

            return true;
        }

        return false;
        
    }

    // TOKEN RENEWAL!

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
            echo $e->getResponse()->getBody(true);
        }

        return rest_ensure_response(json_decode($response->getBody()));
    }

    public function getAppToken()
    {
        $client = new Client();

        try {
            $response = $client->request('GET',
            'https://graph.facebook.com/oauth/access_token?' .
            'grant_type=client_credentials&' .
            'client_id=' . $this->appId . '&' .
            'client_secret=' . $this->appSecret );
        } catch (RequestException $e) {
            echo $e->getResponse()->getBody(true);
        }

        return json_decode($response->getBody())->access_token;
    }

    public function getTokenExpiryDate()
    {
        if($this->facebookToken == ''){
            return false;
        }

        $appToken = $this->getAppToken();
        $client = new Client();

        try {
            $response = $client->request('GET',
            'https://graph.facebook.com/debug_token?' .
            'input_token=' . $this->facebookToken . '&' .
            'access_token=' . $appToken );
        } catch (RequestException $e) {
            echo $e->getResponse()->getBody(true);
        }

        $data = json_decode($response->getBody())->data;

        return $data->expires_at;
 
    }

    // SETUP STUFF

    public function use()
    {
        add_action('admin_menu', [$this,'addMenus']);
        add_action( 'rest_api_init', [$this,'addRoutes']);
        add_action( 'init', [$this,'createPostType'], 50 );
        add_action( 'init', [$this,'createPhotoPostType'], 50 );
        add_action( 'init', [$this,'createAlbumPostType'], 50 );
        add_action( 'init', [$this,'createEventPostType'], 50 );
        $this->cron();
    }

    public function addRoutes() 
    {
        register_rest_route( 'kerigansolutions/v1', '/autoblogtoken',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'exchangeToken' ],
                'permission_callback' => '__return_true'
            ]
        );

        register_rest_route( 'kerigansolutions/v1', '/forcepostsync',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'syncPosts' ],
                'permission_callback' => '__return_true'
            ]
        );

        register_rest_route( 'kerigansolutions/v1', '/forcephotosync',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'syncPhotos' ],
                'permission_callback' => '__return_true'
            ]
        );

        register_rest_route( 'kerigansolutions/v1', '/forceeventsync',
            [
                'methods'  => 'GET',
                'callback' => [ $this, 'syncEvents' ],
                'permission_callback' => '__return_true'
            ]
        );

        // register_rest_route( 'kerigansolutions/v1', '/forcereviewsync',
        //     [
        //         'methods'  => 'GET',
        //         'callback' => [ $this, 'syncReviews' ],
        //         'permission_callback' => '__return_true'
        //     ]
        // );
    }
    
    protected function loadCss()
    {
        wp_enqueue_style('bluma_admin_css', 'https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css',
            false, '1.0.0');
    }

    public function addMenus()
    {
        add_menu_page("Facebook Settings", "Facebook Settings", "administrator", 'kma-facebook', function () {
            $this->loadCss();
            include(wp_normalize_path( dirname(__FILE__) . '/templates/AdminOverview.php'));
        }, "dashicons-admin-generic");
    }

    public function createPostType()
    {
        register_post_type( 'kma-fb-post',
        array(
            'labels' => array(
                'name' => __( 'kma-fb-post' ),
                'singular_name' => __( 'kma-fb-post' )
            ),
            'supports' => ['title','editor','custom-fields'],
            'public' => true,
            'has_archive' => false,
            'rewrite' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_menu' => true,
            'show_in_rest' => true
        ));
    }

    public function createPhotoPostType()
    {
        register_post_type( 'kma-fb-photo',
        array(
            'labels' => array(
                'name' => __( 'kma-fb-photo' ),
                'singular_name' => __( 'kma-fb-photo' )
            ),
            'supports' => ['title','editor','custom-fields'],
            'public' => false,
            'has_archive' => false,
            'rewrite' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_menu' => false
        ));
    }

    public function createAlbumPostType()
    {
        register_post_type( 'kma-fb-album',
        array(
            'labels' => array(
                'name' => __( 'kma-fb-album' ),
                'singular_name' => __( 'kma-fb-album' )
            ),
            'supports' => ['title','editor','custom-fields'],
            'public' => false,
            'has_archive' => false,
            'rewrite' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_menu' => false
        ));
    }

    public function createEventPostType()
    {
        register_post_type( 'kma-fb-event',
        array(
            'labels' => array(
                'name' => __( 'kma-fb-event' ),
                'singular_name' => __( 'kma-fb-event' )
            ),
            'supports' => ['title','editor','custom-fields'],
            'public' => false,
            'has_archive' => false,
            'rewrite' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_menu' => false
        ));
    }

    // public function createReviewPostType()
    // {
    //     register_post_type( 'kma-fb-review',
    //     array(
    //         'labels' => array(
    //             'name' => __( 'kma-fb-review' ),
    //             'singular_name' => __( 'kma-fb-review' )
    //         ),
    //         'supports' => ['title','editor','custom-fields'],
    //         'public' => true,
    //         'has_archive' => false,
    //         'rewrite' => false,
    //     ));
    // }
    
}