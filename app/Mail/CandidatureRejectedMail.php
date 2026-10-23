<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatureRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $decisionMotif;

    public function __construct(public Candidate $candidate, ?string $decisionMotif = null)
    {
        $this->decisionMotif = $decisionMotif ?? $candidate->decision_motif;
    }

    public function envelope(): Envelope
    {
        $rawAddress = config('mail.from.address');
        $fromAddress = is_string($rawAddress) && filter_var($rawAddress, FILTER_VALIDATE_EMAIL)
            ? $rawAddress
            : (filter_var(env('MAIL_FROM_ADDRESS'), FILTER_VALIDATE_EMAIL) ?: 'contact@talentclub.ci');
        $fromName = (string) (config('mail.from.name') ?: (env('MAIL_FROM_NAME') ?: 'Talent Club'));

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName),
            subject: "Mise à jour concernant votre candidature — Talent Club [{$this->candidate->tracking_code}]",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidature_rejected',
        );
    }
}
