<?php

namespace Includes\Modules\Forms;

use Akismet;
use Includes\Modules\Mail\Mail;
use Includes\Modules\Mail\Message;
use ReCaptcha\ReCaptcha;
use Includes\Modules\Core\PostType;
use Includes\Modules\Core\Taxonomy;
use Includes\Modules\Core\MetaBox;

class Form {

  // Setup
  public $postType = 'form-submission';
  public $queryVar = 'form-submissions';
	public $menu_name = 'Form Submissions';
	public $singular = 'Form Submission';
	public $plural = 'Form Submissions';
  public $restRoute = '/submit-form';
  public $icon = 'star-filled';

  // Data
  public $allFields = [
    'name' => 'Name',
    'email' => 'Email Address',
    'phone' => 'Phone Number',
    'comments' => 'Message',
  ];
  public $requiredFields = [
    'name'
  ];

  // Validation
  public $request;
  public $errorCode;
  public $errors = [];
  public $formData;
  public $isSpam;
  public $useRecaptcha = false;
  public $useAkismet = true;
  public $akismetMappings = [
    'email' => 'email'
  ];

  // Email Fields
  public $mailto = 'bryan@kerigan.com';
  public $mailcc = '';
  public $mailbcc = 'websites@kerigan.com';
  public $mailfrom = 'noreply@mg.pwillys.com';
  public $fromName = 'Pineapple Willy\'s';
  public $primaryColor = '#005D25';
  public $secondaryColor = '#FFDA03';
  public $emailSubject = 'New Message From Website';
  public $emailHeadline = 'New Message From Website';
  public $emailBodyText = 'You\'ve received an new message from the website. Submission details are below:';
  public $enableReceipt = true;
  public $receiptSubject = 'Thanks for Contacting Us';
  public $receiptHeadline = 'Thanks for Contacting Us';
  public $receiptBodyText = 'One of our staff members will get back to you as soon as possible. What you submitted is included below:';

  const VALIDATION_ERROR = ['status' => 422];

  public function __construct()
  {
  }

  public function setFields(Array $fields = [])
  {
    $this->allFields = $fields;
  }

  public function setRequiredFields(Array $fields = [])
  {
    $this->requiredFields = $fields;
  }

  public function setRestRoute(String $route)
  {
    $this->restRoute = $route;
  }

  public function submitForm($request)
  {
      $this->request = $request;
      $this->formData = $request->get_params();

      if($this->useAkismet){
        $data['email'] = $this->formData[$this->akismetMappings['email']];
        if(isset($this->akismetMappings['author'])){
          $data['author'] = $this->formData[$this->akismetMappings['author']];
        }
        if(isset($this->akismetMappings['comment'])){
          $data['comment'] = $this->formData[$this->akismetMappings['comment']];
        }
        $this->akismet($data);
      }

      if ($this->hasErrors() && count($this->errors[]) > 0) {
        wp_send_json_error($this->errors, 406);
      }

      if ( function_exists( 'acf_add_local_field_group' ) ) {
        $adminEmail = get_field('email', 'option', false);
        $this->mailto = $adminEmail != false ? $adminEmail : $this->mailto;
      }

      if( $this->formData['mailto'] != null && $this->formData['mailto'] != '' ){
        $this->mailto = $this->formData['mailto'];
      }

      $this->sendEmail();

      if($this->enableReceipt){
        $this->sendBounceback();
      }

      $this->persistToDashboard();

      wp_send_json_success("success", 200);
  }

  public function sendEmail()
  {
    $headers  = 'MIME-Version: 1.0' . PHP_EOL;
    $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
    $headers .= 'From: ' . $this->fromName . ' <' . $this->mailfrom . '>' . PHP_EOL;

    if($this->mailcc != ''){
      $headers .= 'Cc: ' . $this->mailcc . PHP_EOL;
    }

    if($this->mailbcc != ''){
      $headers .= 'Bcc: ' . $this->mailbcc . PHP_EOL;
    }

    if( isset($this->formData['email']) && $this->formData['email'] != null && $this->formData['email'] != '' ){
      $headers .= 'Reply-To: ' . $this->formData['email'] . PHP_EOL;
    }

    $message = new Message();
    $message->setHeadline($this->emailHeadline)
      ->setBody('<p>' . $this->emailBodyText . '</p>' . $this->formDataHTML())
      ->setHeaders($headers)
      ->setSubject($this->emailSubject)
      ->setPrimaryColor($this->primaryColor)
      ->setSecondaryColor($this->secondaryColor)
      ->setPreviewText($this->emailBodyText)
      ->to($this->mailto);

    $mail = new Mail($message);
    $mail->send();
  }

  public function sendBounceback()
  {
    $headers  = 'MIME-Version: 1.0' . PHP_EOL;
    $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
    $headers .= 'From: ' . $this->fromName . ' <' . $this->mailfrom . '>' . PHP_EOL;
    $headers .= 'Reply-To: ' . $this->mailto . PHP_EOL;

    if($this->mailbcc != ''){
      $headers .= 'Bcc: ' . $this->mailbcc . PHP_EOL;
    }

    $message = new Message();
    $message->setHeadline($this->receiptHeadline)
      ->setBody('<p>' . $this->receiptBodyText . '</p>' . $this->formDataHTML())
      ->setHeaders($headers)
      ->setSubject($this->receiptSubject)
      ->setPrimaryColor($this->primaryColor)
      ->setSecondaryColor($this->secondaryColor)
      ->setPreviewText($this->receiptBodyText)
      ->to($this->formData['email']);

      $mail = new Mail($message);
      $mail->send();
  }

  public function formDataHTML()
  {
    $data = '<table cellspacing="0" cellpadding="0" border="0" class="datatable widefat fixed">' .
    '<tr><td colspan="2" style="padding: 0px 3px 0px">';

    foreach($this->allFields as $name => $label){
      if(isset($this->formData[$name])){

        if(is_array($this->formData[$name])){

          $data .= '<tr><td style="padding:3px">' . $label . '</td>
          <td style="padding:3px"><ul>';
          foreach($this->formData[$name] as $key => $var){
            $data .= '<li>' . $var['label'] . ': ' . ($var === 'true' ? 'yes' : ($var === 'false' ? 'no' : $var)) . '</li>';
          }
          $data .= '</ul></td></tr>';

        } else {
          $data .= '<tr><td style="padding:3px">' . $label . '</td>
          <td style="padding:3px">' . $this->formData[$name] . '</td></tr>';
        }

      }
    }

    $data .= '</table>';

    return $data;
  }

  /**
   * Validate form field
   */
  public function hasErrors()
  {
    // Check Akismet for comment spam
    if($this->useAkismet && $this->isSpam != 'false'){
      $this->errorCode = 'akismet_failed';
      $this->errors[] = ($this->isSpam == 'yes' ? 'Akismet validation failed' : $this->isSpam);
      return true;
    }

    // Check Google ReCaptcha
    if($this->useReCaptcha && !$this->validate($this->request->get_param('token'))){
      $this->errorCode = 'recptcha_failed';
      $this->errors[] = 'ReCaptcha validation failed';
      return true;
    }

    // loop through other required fields to make sure they are not blank
    foreach($this->requiredFields as $field){
      if ( $this->formData[$field] === null && $this->formData[$field] !== '') {
        $this->errors[] = 'The ' . $this->allFields[$field] . ' field is required';
        return true;
      }

      // check email formatting
      if($field == 'email'){
        if ( ! filter_var($this->formData['email'], FILTER_VALIDATE_EMAIL)) {
          $this->errors[] = 'The email address you entered is invalid';
          return true;
        }
      }
    }

    return false;
  }

  public function akismet($data)
  {
    // no Akisment installed so return false for spam check
    if( !function_exists( 'akismet_http_post' ) ){
      return 'false';
    }

    global $akismet_api_host, $akismet_api_port;

    // data package to be delivered to Akismet
    $data = array(
      'comment_author_email'  => $data['email'],
      'user_ip'               => $this->getIP(),
      'user_agent'            => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
      'referrer'              => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
      'blog'                  => site_url(),
      'blog_lang'             => 'en_US',
      'blog_charset'          => 'UTF-8',
      'is_test'               => TRUE,
    );

    if(isset($data['author'])){
      $data['comment_author'] = $data['author'];
    }

    if(isset($data['comment'])){
      $data['comment_content'] = $data['comment'];
    }

    // construct the query string
    $query_string = http_build_query( $data );
    // post it to Akismet
    $response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
    // check the results
    $result = ( is_array( $response ) && isset( $response[1] ) ) ? $response[1] : 'false';
    // display the result (it can be 'true', 'false' or some error message )
    $this->isSpam = $result;
  }

  public function validate($recaptchaResponse)
  {
    $valid = false;

    if($recaptchaResponse === ''){
      return $valid;
    }

    $recaptcha = new ReCaptcha(env('GOOGLE_RECAPTCHA_SECRETKEY', 'ABCD'));

    $resp = $recaptcha->setExpectedHostname(env('DOMAIN', 'www.test.com'))
      ->setScoreThreshold(env('SCORE_THRESHOLD', 0.5))
      ->verify($recaptchaResponse, $this->getIP());

    if ($resp->isSuccess()) {
      $valid = true;
    }

    return $valid;
  }

  public function getIP()
  {
    $Ip = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
      $Ip = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
      $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
      $Ip = $_SERVER['REMOTE_ADDR'];
    if (($CommaPos = strpos($Ip, ',')) > 0)
      $Ip = substr($Ip, 0, ($CommaPos - 1));

    return $Ip;
  }

  public function persistToDashboard()
  {
    $defaults = [
      'post_title'  => $this->formData['name'],
      'post_type'   => $this->postType,
      'menu_order'  => 0,
      'post_status' => 'publish'
    ];

    $id = wp_insert_post($defaults);
    foreach ($this->formData as $key => $value) {
      if ($key !== 'name') {

        if(is_array($value)){
          $output = '<ul>';
          foreach($value as $key => $var){
              $output .= '<li>' . $var['label'] . ': ' . ($var['value'] === 'true' ? 'yes' : ($var['value'] === 'false' ? 'no' : $var['value'])) . '</li>';
          }
          $output .= '</ul>';

          update_post_meta($id, $key, $output);

        }else{
          $value = ($value === true ? 'yes' : $value);
          $value = ($value === false ? 'no' : $value);

          update_post_meta($id, $key, $value);

        }
      }
    }

    update_post_meta($id, 'leaddata', $this->formDataHTML());
  }

  public function setPostColumns()
  {
    $columns = ['cb'    => '<input type="checkbox" />'];
    foreach($this->allFields as $key => $var){
      $columns[$key] = $var;
    }
    $columns['date'] = 'Created';

    return $columns;
  }

  public function setColumnContent( $column, $post_id )
  {
    if ( $column == 'name') {
      echo '<a href="' . get_edit_post_link($post_id) . '" >' . get_the_title($post_id) . '</a>';
    }

    foreach($this->allFields as $key => $var){
      if ( $key === $column && $key !== 'name') {
        $content = get_post_meta($post_id, $key, false);

        $i = 0;
        foreach($content as $item){
          $i++;
          echo $item . (count($content) > $i ? ',' : '');
        }
      }
    }
  }

  public function setSortableColumns($columns)
  {
    foreach($this->allFields as $key => $var){
      $columns[$key] = $key;
    }

    return $columns;
  }

  public function setOrdering($query)
  {
    if ( ! is_admin() )
      return;

    $orderby = $query->get( 'orderby');

    foreach($this->allFields as $key => $var){
      if ( $key == $orderby ) {
        $query->set( 'meta_key', $key );
        $query->set( 'orderby', 'meta_value' );
      }
    }
  }

  /**
   * Add REST API routes
   */
  public function registerRoutes()
  {
    register_rest_route(
      'kma/v1',
      $this->restRoute,
      [
        'methods' => 'POST',
        'callback' => [$this, 'submitForm'],
        'permission_callback' => '__return_true'
      ]
    );
  }

  public function use()
  {
    $messages = new PostType($this->postType, $this->queryVar, false, true);
    $messages->labels($this->menu_name, $this->singular, $this->plural, $this->icon);
    $messages->capabilities(['title', 'custom-fields']);
    $messages->make();

    new MetaBox($this->postType, "leaddata", "Lead Data", "data");

    add_filter( 'manage_' . $this->postType . '_posts_columns', [$this, 'setPostColumns'] );
    add_action( 'manage_' . $this->postType . '_posts_custom_column', [$this, 'setColumnContent'], 10, 2);
    add_filter( 'manage_edit-' . $this->postType . '_sortable_columns', [$this, 'setSortableColumns'] );
    add_action( 'pre_get_posts', [$this, 'setOrdering'] );

    add_action( 'admin_menu' , function(){
      remove_meta_box('slugdiv', $this->postType, 'normal');
    });

    add_action('rest_api_init', [$this, 'registerRoutes']);
  }
}
