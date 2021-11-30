<?php

namespace Includes\Modules\Forms;

class Donations extends Form {

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
    'phone'            => 'Phone Number',
    'org_name'         => 'Organization',
    'org_city'         => 'City',
    'org_state'        => 'State',
    'event'            => 'Event',
    'event_date'       => 'Event Date',
    'event_desc'       => 'Event Description',
    'donation_type'    => 'Donation Type',
    'how_many'         => 'Quantity',
    'card_amount'      => 'Card Amount',
    'merch_type'       => 'Desired Merch',
    'food_type'        => 'Desired Food',
    'mailing_address'  => 'Mailing Address',
    'donation_file'    => 'Donation Form',       
    'comments'         => 'Additional Info',
  ];

  public $requiredFields = [
    'email',
    'comments',
  ];

  public $restRoute = '/submit-donation-request';

  public $mailto = 'bryan@kerigan.com';
  public $mailcc = '';
  public $mailbcc = 'websites@kerigan.com';
  public $mailfrom = 'donations@mg.pwillys.com';
  public $fromName = 'Donations';
  public $emailSubject = 'New Donation Request';
  public $emailHeadline = 'New Donation Request';
  public $emailBodyText = 'You\'ve received an new donation request on the website. Details are below:';

  public $enableReceipt = true;
  public $receiptSubject = 'Thanks for requesting a donation';
  public $receiptHeadline = 'Thanks for requesting a donation';
  public $receiptBodyText = 'We are honored that you thought of us to help with your fundraiser or event. We try to support as many local, non-profit organizations as we possibly can but we receive a ton of charitable inquiries and often the number of applications we receive exceeds the funds / resources available and we are honestly unable to honor all requests. Please accept our apologies if we don\'t write back! We will solely reach out to folks whose requests we can facilitate. Thank you for your understanding!';

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
    echo '<donations-form nonce="' .wp_create_nonce( 'wp_rest' ) . '" ></donations-form>';
  }
}
