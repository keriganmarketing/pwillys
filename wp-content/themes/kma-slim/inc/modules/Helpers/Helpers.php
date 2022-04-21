<?php

namespace Includes\Modules\Helpers;

class Helpers {

  /**
   * Return the real IP of current request
   */
  public function getIP ()
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

  /**
   * Return the user agent of current request
   */
  public function getUserAgent ()
  {
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
  }

  /**
   * Get referrer
   */
  public function getReferrer ()
  {
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
  }
}
