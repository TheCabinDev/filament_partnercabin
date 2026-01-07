<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawSuccessNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private $partner,
        private $amount,
        private $method,
        // private $accountNumber,
        private $dateTransaction
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match (app()->environment()) {
                'local'   => 'TEST LOCAL: Withdraw Dana Berhasil Dilakukan',
                'staging' => 'TEST STAGING: Withdraw Dana Berhasil Dilakukan',
                default   => 'Withdraw Dana Berhasil Dilakukan',
            },
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdraw-success',
            with: [
                'name' => $this->partner->name,
                'amount' => $this->amount,
                'method' => $this->method,
                // 'account_number' => $this->accountNumber,
                'date_transaction' => $this->dateTransaction,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
