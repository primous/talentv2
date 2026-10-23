<?php

namespace App\Mail;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectStepClientActionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ProjectRequest $projectRequest,
        public ProjectStep $step,
        public Candidate $candidate,
        public string $actionTitle,
        public string $actionMessage
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
            subject: "[{$this->projectRequest->reference}] Action client : {$this->actionTitle} — Talent Club",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project_step_client_action_notification',
        );
    }
}
