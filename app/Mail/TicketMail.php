<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Karina\Registration;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Registration instance.
     *
     * @var \App\Karina\Registration
     */
    public $registration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticket')
                    ->with('registration', $this->registration)
                    ->from(config('mail.from.address'), $this->registration->event->user->name)
                    ->subject('Your ticket for '.$this->registration->event->title);
    }
}
