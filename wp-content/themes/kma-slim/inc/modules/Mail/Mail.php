<?php
namespace Includes\Modules\Mail;

class Mail
{
    public $message;
    public $companyName;
    public $url;
    public $logo;
    public $headline;
    public $year;

    public function __construct(Message $message)
    {
        $this->url         = get_bloginfo('url');
        $this->message     = $message;
        $this->companyName = get_bloginfo('name');
        $this->year        = date('Y');
    }

    public function send()
    {
        wp_mail($this->message->to, $this->message->subject, $this->formattedEmail(), $this->message->headers);
    }

    public function formattedEmail()
    {
        return <<< EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{$this->companyName}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style type="text/css">
    body, html {
      width: 100% !important;
      height: 100%;
      margin: 0;
      line-height: 1.4;
      background-color: #EEEEEE;
      -webkit-text-size-adjust: none;
      font-family: 'Open Sans', sans-serif;
      font-weight: 400;
    }
    .datatable {
      width: 100% !important;
    }
    .datatable td {
      border: 1px solid #FFF !important;
      padding: 3px 5px !important;
    }
    @media only screen and (max-width: 600px) {
      .content-cell,
      .email-body_inner,
      .email-footer {
        width: 100% !important;
      }

    }
    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
      }
    }
    </style>
  </head>
  <body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E;  height: 100%; line-height: 1.4; margin: 0; width: 100% !important;" bgcolor="#EEEEEE">
    <span class="preheader" style="box-sizing: border-box; display: none !important;  font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; mso-hide: all; opacity: 0; overflow: hidden; visibility: hidden;">{$this->message->previewText}</span>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box;  margin: 0; padding: 0; width: 100%;" bgcolor="#EEEEEE">
      <tr>
        <td align="center" style="box-sizing: border-box;  word-break: break-word;">
          <table class="email-content" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box;  margin: 0; padding: 0; width: 100%;">
            <tr>
              <td class="email-body" width="100%" cellpadding="0" cellspacing="0" style="-premailer-cellpadding: 0; -premailer-cellspacing: 0; box-sizing: border-box;  margin: 0; padding: 0; width: 100%; word-break: break-word;" >
                <table class="email-body_inner" align="center" cellpadding="0" cellspacing="0" style="box-sizing: border-box;  margin: 0 auto; padding: 0;" bgcolor="#FFFFFF">
                  <tr>
                    <td class="content-cell" style="width:550px; box-sizing: border-box;  padding: 35px; word-break: break-word;">
                      <h1 style="box-sizing: border-box; color: {$this->message->primaryColor}; font-size: 34px;  margin-top: 0;" align="left">{$this->message->headline}</h1>
                      <p style="box-sizing: border-box; color: #333333;  font-size: 16px; line-height: 20px; margin-top: 0;" align="left">{$this->message->body}</p>
                  </tr>
                  <tr>
                    <td class="content-cell" style="box-sizing: border-box;  word-break: break-word; padding:35px; border-bottom: 15px solid {$this->message->primaryColor}">
                      <p style="box-sizing: border-box; color: #555555; font-size: 12px; line-height: 16px; margin-top: 0;" >&copy; {$this->year} {$this->companyName}. All rights reserved.</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
EOD;
    }
}
