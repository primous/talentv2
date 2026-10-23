<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientMagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $clientEmail,
        public string $accessUrl,
        public ?string $clientName = null,
        public int $lifetimeDays = 14
    ) {
    }

    public function envelope(): Envelope
    {
        $rawAddress = config('mail.from.address');
        $fromAddress = is_string($rawAddress) && filter_var($rawAddress, FILTER_VALIDATE_EMAIL)
            ? $rawAddress
            : (filter_var(env('MAIL_FROM_ADDRESS'), FILTER_VALIDATE_EMAIL) ?: 'contact@talentclub.ci');
        $fromName = (string) (config('mail.from.name') ?: (env('MAIL_FROM_NAME') ?: 'Talent Club'));

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: "Lien d'accès à votre espace client — Talent Club",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client_magic_link',
        );
    }
}
