<?php

namespace App\Mail\Admin;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        $fromEmail = Configuration::get('smtp_from_email', 'noreply@conocetandil.com');
        $fromName  = Configuration::get('smtp_from_name', 'Conoce Tandil');

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: 'Nuevo usuario registrado — Conoce Tandil',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-user',
        );
    }
}
