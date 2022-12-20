<?php

namespace Includes\Modules\Forms;

class Appointment extends Form {

  public $postType = 'survey-response';
  public $queryVar = 'survey-responses';
  public $menu_name = 'Survey Responses';
  public $singular = 'Survey Response';
  public $plural = 'Survey Responses';

  public $allFields = [
    'email'            => 'Email Address',
    'comments'         => 'Message',
  ];

  public $requiredFields = [
    'email',
    'comments',
  ];

  public $restRoute = '/submit-survey-response';

  public $mailto = 'jared@pwillys.com';
  public $mailcc = '';
  public $mailbcc = 'websites@kerigan.com';
  public $mailfrom = 'surveys@mg.pwillys.com';
  public $fromName = 'Customer Care';
  public $emailSubject = 'New Customer Satisfaction Survey Response';
  public $emailHeadline = 'New Customer Satisfaction Survey Response';
  public $emailBodyText = 'You\'ve received an new customer satisfaction survey response from the website. Details are below:';

  public $enableReceipt = true;
  public $receiptSubject = 'Thanks for helping us';
  public $receiptHeadline = 'Thanks for helping us';
  public $receiptBodyText = 'Our goal is to provide you with the best possible service. Thank you for taking the time to help us.';

  public $useRecaptcha = false;
  public $useAkismet = true;

  // Maps form fields to akismet for spam detection
  // allowed keys: email (required), comment, author
  public $akismetMappings = [
    'email'   => 'email',
    'comment' => 'comments'
  ];
}
