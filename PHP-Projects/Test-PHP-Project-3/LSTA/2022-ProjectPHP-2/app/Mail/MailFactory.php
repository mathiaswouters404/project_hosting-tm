<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailFactory extends Mailable
{

    use Queueable, SerializesModels;

    public $body, $toAddress, $toName, $ccAddress, $ccName, $subject;

    /** Create a new message instance. ...*/
    public function __construct($body,$toAddress,$ccAddress,$ccName, $subject)
    {
        $this->body = $body;
        $this->toAddress = $toAddress;
        $this->ccAddress = $ccAddress;
        $this->ccName = $ccName;
        $this->subject = $subject;
    }

    /** Build the message. ...*/
    public function build()
    {
        return $this
            ->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"))
            ->cc($this->ccAddress, $this->ccName)
            ->subject($this->subject)
            ->markdown('email.template');
    }

    public function SendMail(){
        Mail::to($this->toAddress)
            ->send($this);
    }
}
