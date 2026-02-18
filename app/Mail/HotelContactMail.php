<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Hotel  $hotel,
        public string $senderName,
        public string $senderEmail,
        public string $senderPhone,
        public string $contactMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consulta desde Conoce Tandil — ' . $this->hotel->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hotel-contact',
        );
    }
}
