<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderAfter extends Mailable
{
    use Queueable, SerializesModels;

    public $displayTask;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($displayTask)
    {
        $this->displayTask = $displayTask;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.reminder.after')
            ->from('reminder@mail.com')
            ->subject('Reminder vergeten: ' . $this->displayTask['name']);
    }
}
