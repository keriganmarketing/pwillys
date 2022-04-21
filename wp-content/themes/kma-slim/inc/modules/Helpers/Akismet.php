<?php

namespace Includes\Modules\Helpers;

use Includes\Modules\Helpers\Helpers;

class Akismet {

  public $helpers;

  public function __construct ()
  {
    $this->helpers = new Helpers;
  }

  /**
   * Check if Akismet is installed and active
   */
  public function isActive ()
  {
    return function_exists( 'akismet_http_post' );
  }

  /**
   * Check if object contains spam content
   *
   * @param Array $data['comment_author_email','user_ip','user_agent','referrer','comment_author','comment_content']
   */
  public function checkSpam ($data)
  {

    // not spam if akismet isn't active
    if(!$this->isActive()) {
      return false;
    }

    global $akismet_api_host, $akismet_api_port;

    // data package to be delivered to Akismet
    $commentData = [
      'comment_author_email'  => $data['comment_author_email'], //required
      'blog'                  => site_url(),
      'blog_lang'             => 'en_US',
      'blog_charset'          => 'UTF-8',
      'is_test'               => TRUE,
    ];

    if(isset($data['user_ip'])){
      $commentData['user_ip'] = $data['user_ip'];
    }

    if(isset($data['user_agent'])){
      $commentData['user_agent'] = $data['user_agent'];
    }

    if(isset($data['referrer'])){
      $commentData['referrer'] = $data['referrer'];
    }

    if(isset($data['comment_author'])){
      $commentData['comment_author'] = $data['comment_author'];
    }

    if(isset($data['comment_content'])){
      $commentData['comment_content'] = $data['comment_content'];
    }

    // construct the query string
    $query_string = http_build_query( $commentData );
    // post it to Akismet
    $response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );

    // the result is the second item in the array, boolean
    return $response[1] == 'true' ? true : false;
  }
}
