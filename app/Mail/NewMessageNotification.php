<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $message,
        public Form    $form,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje de ' . $this->form->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-message',
        );
    }
}
