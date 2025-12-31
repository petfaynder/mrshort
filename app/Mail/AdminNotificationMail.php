<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class AdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $type;
    public array $data;

    /**
     * Create a new message instance.
     * 
     * @param string $type Type of notification: 'new_user', 'new_withdrawal'
     * @param array $data Associated data for the notification
     */
    public function __construct(string $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'MrShort');
        
        $subjects = [
            'new_user' => "[Admin] New User Registration - {$siteName}",
            'new_withdrawal' => "[Admin] New Withdrawal Request - {$siteName}",
        ];
        
        return new Envelope(
            subject: $subjects[$this->type] ?? "[Admin] Notification - {$siteName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification',
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
