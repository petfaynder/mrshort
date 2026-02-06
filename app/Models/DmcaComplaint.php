<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DmcaComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'link_code',
        'original_url',
        'complaint_type',
        'reporter_name',
        'reporter_email',
        'reporter_ip',
        'description',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Complaint type labels
     */
    public static function complaintTypeLabels(): array
    {
        return [
            'copyright' => 'Copyright Infringement',
            'malware' => 'Malware / Malicious Content',
            'illegal' => 'Illegal Content',
            'phishing' => 'Phishing / Fraud',
            'sexual_content' => 'Sexual Content / Leaks',
            'other' => 'Other',
        ];
    }

    /**
     * Status labels
     */
    public static function statusLabels(): array
    {
        return [
            'pending' => 'Pending',
            'reviewing' => 'Reviewing',
            'resolved' => 'Resolved',
            'rejected' => 'Rejected',
        ];
    }

    /**
     * Get the label for this complaint's type
     */
    public function getComplaintTypeLabelAttribute(): string
    {
        return self::complaintTypeLabels()[$this->complaint_type] ?? $this->complaint_type;
    }

    /**
     * Get the label for this complaint's status
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Relationship to the link
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
