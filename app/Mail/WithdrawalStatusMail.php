<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\WithdrawRequest;
use App\Models\SiteSetting;

class WithdrawalStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public WithdrawRequest $withdrawal;
    public string $status;
    public ?string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(WithdrawRequest $withdrawal, string $status, ?string $reason = null)
    {
        $this->withdrawal = $withdrawal;
        $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'MrShort');
        
        $subjects = [
            'approved' => "Withdrawal Request Approved - {$siteName}",
            'completed' => "Withdrawal Completed - {$siteName}",
            'cancelled' => "Withdrawal Request Cancelled - {$siteName}",
            'rejected' => "Withdrawal Request Rejected - {$siteName}",
        ];
        
        return new Envelope(
            subject: $subjects[$this->status] ?? "Withdrawal Update - {$siteName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawal-status',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
