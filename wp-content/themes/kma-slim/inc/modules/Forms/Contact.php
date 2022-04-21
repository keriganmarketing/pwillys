<?php

namespace Includes\Modules\Forms;

class Contact extends Form {

  public $postType = 'donation-request';
  public $queryVar = 'donation-requests';
  public $menu_name = 'Donation Requests';
  public $singular = 'Donation Request';
  public $plural = 'Donation Requests';
  public $icon = 'money-alt';

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

  public $restRoute = '/submit-contact-request';

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

  public $useRecaptcha = false;
  public $useAkismet = true;

  // Maps form fields to akismet for spam detection
  // allowed keys: email (required), comment, author
  public $akismetMappings = [
    'email'   => 'email',
    'comment' => 'comments'
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
