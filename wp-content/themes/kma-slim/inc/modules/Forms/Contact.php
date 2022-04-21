<?php

namespace Includes\Modules\Forms;

class Contact extends Form {

  public $postType = 'contact-request';
  public $queryVar = 'contact-requests';
  public $menu_name = 'Contact Requests';
  public $singular = 'Contact Request';
  public $plural = 'Contact Requests';
  public $icon = 'email';

  public $allFields = [
    'fname'            => 'First Name',
    'lname'            => 'Last Name',
    'email'            => 'Email Address',
    'comments'         => 'Additional Info',
  ];

  public $requiredFields = [
    'email',
    'comments',
  ];

  public $restRoute = '/submit-contact-form';

  // public $mailto = 'jaredknetzer@gmail.com';
  public $mailto = 'bryan@kerigan.com';
  public $mailcc = '';
  public $mailbcc = 'websites@kerigan.com';
  public $mailfrom = 'notifications@mg.pwillys.com';
  public $fromName = 'Pineapple Willys';
  public $emailSubject = 'New Contact Request';
  public $emailHeadline = 'New Contact Request';
  public $emailBodyText = 'You\'ve received an new message from the website. Details are below:';

  public $enableReceipt = true;
  public $receiptSubject = 'Thanks for contacting us';
  public $receiptHeadline = 'Thanks for contacting us';
  public $receiptBodyText = 'One of our staff members will reach back out to your as soon as possible. What you submitted is below.';

  public $useAkismet = true;

  // Maps form fields to akismet for spam detection
  // allowed keys: email (required), comment, author
  public $akismetMappings = [
    'comment_author_email' => 'email',
    'comment_author' => 'fname',
    'comment_content' => 'comments',
    'user_ip' => 'user_ip',
    'user_agent' => 'user_agent',
    'referrer' => 'referrer',
  ];

  public function makeShortcode($atts)
  {
    ob_start();
    echo '<contact-form 
      nonce="' .wp_create_nonce( 'wp_rest' ) . '" 
      user-ip="' . $this->helpers->getIP() . '"
      user-agent="' . $this->helpers->getUserAgent() . '"
      referrer="' . $this->helpers->getReferrer() . '"
    ></contact-form>';
    return ob_get_clean();
  }
}
