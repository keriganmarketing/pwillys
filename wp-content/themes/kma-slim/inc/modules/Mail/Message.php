<?php
namespace Includes\Modules\Mail;

class Message
{
    public $to;
    public $body;
    public $headers;
    public $subject;
    public $headline;
    public $previewText = '';
    public $primaryColor = '#000000';
    public $secondaryColor = '#555555';

    public function __construct()
    {
        // Turtle silence
    }

    public function setPrimaryColor(string $hex)
    {
        $this->primaryColor = $hex;

        return $this;
    }

    public function setSecondaryColor(string $hex)
    {
        $this->secondaryColor = $hex;

        return $this;
    }

    public function to(string $to = 'bbaird85@gmail.com')
    {
        $this->to = $to;

        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setHeaders(string $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    public function setHeadline(string $headline)
    {
        $this->headline = $headline;

        return $this;
    }

    public function setPreviewText(string $previewText)
    {
        $this->previewText = $previewText;

        return $this;
    }
}
