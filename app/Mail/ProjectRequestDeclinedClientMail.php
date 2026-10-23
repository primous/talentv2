<?php

namespace App\Mail;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectRequestDeclinedClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $trackingUrl;

    public function __construct(
        public ProjectRequest $projectRequest,
        public ?Candidate $candidate = null,
        ?string $trackingUrl = null
    ) {
        if ($trackingUrl) {
            $this->trackingUrl = $trackingUrl;
        } elseif (!empty($this->projectRequest->client_email)) {
            try {
                $tokenData = \App\Models\ClientAccessToken::generateForEmail($this->projectRequest->client_email);
                $this->trackingUrl = $tokenData['url'];
            } catch (\Throwable $e) {
                $this->trackingUrl = route('client.demandes.index');
            }
        } else {
            $this->trackingUrl = route('client.demandes.index');
        }
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
            subject: "Information concernant votre demande de projet [{$this->projectRequest->reference}] — Talent Club",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project_request_declined_client',
        );
    }
}
