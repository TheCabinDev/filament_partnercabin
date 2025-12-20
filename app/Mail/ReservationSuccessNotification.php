<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationSuccessNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $partnerCode;
    public $reservationId;
    public $totalPrice;
    public $earnedPoin;
    public $dateTransaction;

    /**
     * Create a new message instance.
     */
    public function __construct($partner, $partnerCode, $reservationId, $totalPrice, $earnedPoin, $dateTransaction)
    {
        $this->partner = $partner;
        $this->partnerCode = $partnerCode;
        $this->reservationId = $reservationId;
        $this->totalPrice = $totalPrice;
        $this->earnedPoin = $earnedPoin;
        $this->dateTransaction = $dateTransaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Berhasil',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-success',
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
