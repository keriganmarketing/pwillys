<?php

namespace Includes\Modules\Reviews;

use KeriganSolutions\CPT\CustomPostType;

/**
 * Reviews
 */

// Exit if accessed directly.
if ( ! defined('ABSPATH')) {
    exit;
}

class Reviews
{
    /**
     * Reviews constructor.
     */
    public function __construct()
    {
    }

    public function setupAdmin()
    {
        $this->createPostType();
        $this->createAdminColumns();
        $this->setupShortcode();
    }

    /**
     * @return null
     */
    public function createPostType()
    {
        $quote = new CustomPostType('Review', [
            'supports'           => ['title', 'editor', 'revisions'],
            'menu_icon'          => 'dashicons-format-quote',
            'rewrite'            => ['slug' => 'reviews'],
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => true,
            'publicly_queryable' => true,
        ]);

        $quote->addTaxonomy('Review Category');

        $quote->addMetaBox('Author Info', [
            'Name'     => 'text',
            'Company'  => 'text',
            'Location' => 'text',
            'Stars'    => 'text',
            'Date'     => 'text',
            'Featured' => 'boolean'
        ]);
    }

    /**
     * @return null
     */
    public function createAdminColumns()
    {
        add_filter('manage_review_posts_columns',
            function ($defaults) {
                $defaults = [
                    'cb'       => '<input type="checkbox">',
                    'title'    => 'Author',
                    'company'  => 'Company',
                    'review'   => 'Review',
                    'location' => 'Location',
                    'stars'    => 'Rating',
                    'featured' => 'Featured',
                    'date'     => 'Date'
                ];

                return $defaults;
            }, 0);

        add_action('manage_review_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {

                case 'company':
                    $content = get_post_meta($post_ID, 'author_info_company', true);
                    echo(isset($content) ? $content : null);
                    break;

                case 'featured':
                    $content = get_post_meta($post_ID, 'author_info_featured', true);
                    echo($content == 'on' ? 'TRUE' : 'FALSE');
                    break;

                case 'location':
                    $content = get_post_meta($post_ID, 'author_info_location', true);
                    echo(isset($content) ? $content : null);
                    break;

                case 'stars':
                    $content = get_post_meta($post_ID, 'author_info_stars', true);
                    echo(isset($content) ? $content : null);
                    break;

                case 'review':
                    $content = get_post_field('post_content', $post_ID);
                    echo(isset($content) ? $content : null);
                    break;
            }
        }, 0, 2);
    }

    public function setupShortcode()
    {
        add_shortcode('reviews', function ($atts) {
            return $this->displayReviews($atts);
        });
    }

    public function displayReviews($atts)
    {
        $reviews  = $this->getReviews();
        $template = file_get_contents(wp_normalize_path(dirname(__FILE__) . '/review-template.php'));
        $output   = '<div class="review-module">';
        foreach ($reviews as $review) {
            $newReview = str_replace('{review-content}', $review['content'], $template);
            $newReview = str_replace('{review-author}', $review['author'], $newReview);
            $newReview = str_replace('{review-company}', $review['company'], $newReview);
            $newReview = str_replace('{review-rating}', $review['rating'], $newReview);
            $newReview = str_replace('{review-date}', $review['date'], $newReview);
            $newReview = str_replace('{review-location}', ', ' . $review['location'], $newReview);
            $output    .= $newReview;
        }
        $output .= '</div>';

        return $output;
    }

    public function getRandomReview()
    {

        $request = $this->getReviews([
            'order'          => 'rand',
            'posts_per_page' => 1
        ]);

        return $request[0];
    }

    public function getRecentReview()
    {

        $request = $this->getReviews([
            'posts_per_page' => 1
        ]);

        array_multisort($request, 'SORT_DESC','SORT_NUMERIC');

        return $request[0];
    }

    public function getReviews($args = [])
    {
        $outputArray = [];

        $request = [
            'posts_per_page' => -1,
            'offset'         => 0,
            'order'          => 'DESC',
            'orderby'        => 'date_posted',
            'post_type'      => 'review',
            'post_status'    => 'publish',
        ];

        $request   = array_merge($request, $args);
        $postArray = get_posts($request);

        foreach ($postArray as $post) {
            $outputArray[] = [
                'content'  => $post->post_content,
                'author'   => get_post_meta($post->ID, 'author_info_name', true),
                'company'  => get_post_meta($post->ID, 'author_info_company', true),
                'featured' => get_post_meta($post->ID, 'author_info_featured', true),
                'date'     => get_post_meta($post->ID, 'author_info_date', true),
                'location' => get_post_meta($post->ID, 'author_info_location', true),
                'rating'   => get_post_meta($post->ID, 'author_info_stars', true)
            ];
        }

        return $outputArray;
    }
}
