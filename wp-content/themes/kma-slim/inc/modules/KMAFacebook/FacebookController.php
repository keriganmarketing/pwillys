<?php

namespace Includes\Modules\KMAFacebook;

use KeriganSolutions\FacebookFeed\FacebookFeed;
use KeriganSolutions\FacebookFeed\FacebookReviews;
use KeriganSolutions\FacebookFeed\Fetchers\FacebookRequest;

class FacebookController
{
    protected $facebookPageID;
    protected $facebookToken;

    public function __construct()
    {
        $this->facebookPageID = get_option('facebook_page_id');
        $this->facebookToken = get_option('facebook_token');
        add_action( 'init', [$this,'createPostType'], 50 );
        $this->cron();
        // $this->updatePosts();
    }

    public function getReviews($num = 1)
    {
        $feed = new FacebookReviews($this->facebookPageID,$this->facebookToken);
        return $feed->fetch($num);
    }

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
            $post->attachments = get_post_meta($post->ID, 'attachments', false);
            $post->type = get_post_meta($post->ID, 'type', true);
            $post->status_type = get_post_meta($post->ID, 'status_type', true);
            $post->video_url = get_post_meta($post->ID, 'video_url', true);
            $post->video_image = get_post_meta($post->ID, 'video_image', true);
            $output[] = $post;
        }

        return $output;
    }

    public function getFeed($num = 1)
    {
        try{
            $feed = new FacebookFeed($this->facebookPageID,$this->facebookToken);
            $response = $feed->fetch($num);
        }catch(Exception $e){
            $response = $e->getResponse()->getBody(true);
        }

        return $response;

    }

    public function setupAdmin()
    {
        add_action('admin_menu', function(){
            $this->addMenus();
        });
    }

    protected function loadCss()
    {
        wp_enqueue_style('bluma_admin_css', 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css',false, '1.0.0');
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
        ));
    }

    public function cron()
    {
        if(! wp_next_scheduled('kma-fb-sync')){
            wp_schedule_event(strtotime('01:00:00'), 'hourly', 'kma-fb-sync');
        }

        add_action('kma-fb-sync', [$this,'updatePosts']);
    }

    public function updatePosts()
    {
        $feed = $this->getFeed(30);

        if($feed->posts){

            foreach($feed->posts as $fbpost){

                echo '<pre>',print_r($fbpost),'</pre>';

                $postArray = [
                    'ID' => 0,
                    'post_date' => $fbpost->created_time,
                    'post_content' => $fbpost->message,
                    'post_title' => $fbpost->object_id,
                    'post_status' => 'publish',
                    'post_type' => 'kma-fb-post',
                    'meta_input' => [
                        'post_link' => $fbpost->permalink_url,
                        'full_image_url' => $fbpost->full_picture,
                        'attachments' => '',
                        'type' => $fbpost->type,
                        'status_type' => $fbpost->status_type,
                        'video_url' => $fbpost->attachments->data[0]->media->source,
                        'video_image' => $fbpost->attachments->data[0]->media->image->src
                    ]
                ];

                $postExists = get_page_by_title($fbpost->object_id, OBJECT, 'kma-fb-post');
                // echo '<pre>',print_r($postExists),'</pre>';

                if(isset($postExists->ID)){
                    $postArray['ID'] = $postExists->ID;
                    wp_update_post($postArray);
                    update_post_meta($postExists->ID, 'attachments', $fbpost->media);

                }else{
                    $postID = wp_insert_post($postArray);
                    update_post_meta($postID, 'attachments', $fbpost->media);
                    
                }
                
                
                
            }
        }

    }


}
