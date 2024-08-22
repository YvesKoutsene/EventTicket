<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentLink;

    /**
     * Create a new message instance.
     *
     * @param  string  $paymentLink
     * @return void
     */
    public function __construct($paymentLink)
    {
        $this->paymentLink = $paymentLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payment_link')
            ->with([
                'paymentLink' => $this->paymentLink,
            ]);
    }
}
