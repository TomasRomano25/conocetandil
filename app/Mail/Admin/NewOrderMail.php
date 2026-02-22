<?php

namespace App\Mail\Admin;

use App\Models\Configuration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $order,
        public string $type  // 'membership' | 'hotel'
    ) {}

    public function envelope(): Envelope
    {
        $fromEmail = Configuration::get('smtp_from_email', 'noreply@conocetandil.com');
        $fromName  = Configuration::get('smtp_from_name', 'Conoce Tandil');

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: 'Nuevo pedido recibido — Conoce Tandil',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-order',
            with: [
                'order' => $this->order,
                'type'  => $this->type,
            ],
        );
    }
}
