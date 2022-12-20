<?php

namespace Includes\Modules\Forms;

use Includes\Modules\Mail\Mail;
use Includes\Modules\Mail\Message;
use Includes\Modules\Core\PostType;
use Includes\Modules\Core\Taxonomy;
use Includes\Modules\Core\MetaBox;
use Includes\Modules\Helpers\Akismet;
use Includes\Modules\Helpers\Helpers;

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
  public $akismet;
  public $helpers;
  public $request;
  public $files;
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
  public $mailto = 'jared@pwillys.com';
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

  public $uploads = [];

  const VALIDATION_ERROR = ['status' => 422];

  public function __construct()
  {
    $this->akismet = new Akismet;
    $this->helpers = new Helpers;
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
        $data = [];
        foreach($this->akismetMappings as $key => $var) {
          $data[$key] = $this->formData[$var];
        }
        $this->isSpam = $this->akismet->checkSpam($data);

      }

      if(count($this->uploads) > 0){        
        foreach($this->uploads as $upload){
          $this->formData[$upload] = $this->uploadFile(
            $this->formData[$upload]
          );
        }
      }

      if ($this->hasErrors()) {
        wp_send_json_error($this->errors, $this->errorCode, 406);
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

  public function uploadFile($base64string)
  {
		require( ABSPATH . 'wp-load.php' );

    if (strpos($base64string, ',') !== false) {
      $fileParts = explode(',', $base64string);
      $type64 = explode(';', $fileParts[0]);
      $type = explode(':', $type64[0]);
      $extension = explode('/', $type[1]);
    }

    $decoded_file = base64_decode($fileParts[1], true);

    $file = [
      'data' => $decoded_file,
      'type' => $type[1],
      'name' => date('YmdHis') . '.' . $extension[1]
    ];

		$wordpress_upload_dir = wp_upload_dir();
		$new_file_path = wp_normalize_path($wordpress_upload_dir['path'] . '/' . $file['name']);
    $upload_file = file_put_contents( $new_file_path, $file['data'] );

    $attachment = array(
      'post_mime_type' => $file['type'],
      'post_title'     => $file['name'],
      'post_content'   => '',
      'post_status'    => 'inherit',
      'guid'           => $wordpress_upload_dir['url'] . '/' . basename( $file['name'] )
    );
  
    $upload_id = wp_insert_attachment( $attachment, $new_file_path );

    return wp_get_attachment_url($upload_id);

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
    $data = '';

    foreach($this->allFields as $name => $label){
      if(isset($this->formData[$name])){

        if(is_array($this->formData[$name])){

          $data .= '<p><strong>' . $label . '</strong><br>
          <ul>';
          foreach($this->formData[$name] as $key => $var){
            $data .= '<li>' . $var['label'] . ': ' . ($var === 'true' ? 'yes' : ($var === 'false' ? 'no' : $var)) . '</li>';
          }
          $data .= '</ul></p>';

        } else {
          $data .= '<p><strong>' . $label . '</strong><br>' . 
          $this->formData[$name] . '</p>';
        }

      }
    }

    $data .= '';

    return $data;
  }

  /**
   * Validate form field
   */
  public function hasErrors()
  {
    // Check Akismet for comment spam
    if($this->isSpam){
      $this->errorCode = 'akismet_failed';
      $this->errors[] = 'This message has been flagged as spam.';
    }

    // loop through other required fields to make sure they are not blank
    foreach($this->requiredFields as $field){
      if ( $this->formData[$field] === null && $this->formData[$field] !== '') {
        $this->errorCode = 'missing_required_fields';
        $this->errors[] = 'The ' . $this->allFields[$field] . ' field is required.';
      }

      // check email formatting
      if($field == 'email'){
        if ( ! filter_var($this->formData['email'], FILTER_VALIDATE_EMAIL)) {
          $this->errorCode = 'email_formatted_badly';
          $this->errors[] = 'The email address you entered is invalid.';
        }
      }
    }

    if(count($this->errors) > 0){
      return true;
    }

    return false;
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
    if ( !is_admin() ){
      return;
    }

    $orderby = $query->get( 'orderby');

    foreach($this->allFields as $key => $var){
      if ( $key == $orderby ) {
        $meta_query = array(
          'relation' => 'OR',
          array(
            'key' => $key,
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key' => $key,
          ),
        );

        $query->set( 'meta_query', $meta_query );
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
