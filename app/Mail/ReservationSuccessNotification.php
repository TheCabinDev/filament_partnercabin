<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ReservationSuccessNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $partner, public $partnerCode, private $reservationId, private $totalPrice, private $earnedPoin, private $dateTransaction) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match (app()->environment()) {
                'local'   => 'TEST LOCAL:Reservasi Baru Berhasil',
                'staging' => 'TEST STAGING:Reservasi Baru Berhasil',
                default   => 'Reservasi Baru Berhasil',
            },
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-success',
            with: [
                'name' => $this->partner,
                'unique_code' =>  $this->partnerCode,
                'reservation_id' =>  $this->reservationId,
                'total_price' =>  $this->totalPrice,
                'earned_cash' =>  $this->earnedPoin,
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
