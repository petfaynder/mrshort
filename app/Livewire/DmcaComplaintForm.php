<?php

namespace App\Livewire;

use App\Models\DmcaComplaint;
use App\Models\Link;
use Livewire\Component;

class DmcaComplaintForm extends Component
{
    public ?Link $link = null;
    public string $linkCode = '';
    public string $originalUrl = '';
    
    public string $complaintType = '';
    public string $reporterName = '';
    public string $reporterEmail = '';
    public string $description = '';
    
    public bool $submitted = false;
    public string $errorMessage = '';

    protected $rules = [
        'complaintType' => 'required|in:copyright,malware,illegal,phishing,sexual_content,other',
        'reporterName' => 'required|string|min:2|max:255',
        'reporterEmail' => 'required|email|max:255',
        'description' => 'required|string|min:20|max:5000',
    ];

    protected $messages = [
        'complaintType.required' => 'Please select a complaint type.',
        'complaintType.in' => 'Invalid complaint type.',
        'reporterName.required' => 'Please enter your name.',
        'reporterName.min' => 'Name must be at least 2 characters.',
        'reporterEmail.required' => 'Please enter your email address.',
        'reporterEmail.email' => 'Please enter a valid email address.',
        'description.required' => 'Please enter a description.',
        'description.min' => 'Description must be at least 20 characters.',
    ];

    public function mount(string $linkCode)
    {
        $this->linkCode = $linkCode;
        $this->link = Link::where('code', $linkCode)->first();
        
        if ($this->link) {
            $this->originalUrl = $this->link->original_url;
        } else {
            $this->errorMessage = 'The specified link was not found.';
        }
    }

    public function submit()
    {
        if (!$this->link) {
            $this->errorMessage = 'Unable to submit complaint for this link.';
            return;
        }

        $this->validate();

        try {
            DmcaComplaint::create([
                'link_id' => $this->link->id,
                'link_code' => $this->linkCode,
                'original_url' => $this->originalUrl,
                'complaint_type' => $this->complaintType,
                'reporter_name' => $this->reporterName,
                'reporter_email' => $this->reporterEmail,
                'reporter_ip' => request()->ip(),
                'description' => $this->description,
                'status' => 'pending',
            ]);

            $this->submitted = true;
        } catch (\Exception $e) {
            \Log::error('DMCA complaint submission failed', ['error' => $e->getMessage()]);
            $this->errorMessage = 'An error occurred while submitting your complaint. Please try again.';
        }
    }

    public function render()
    {
        return view('livewire.dmca-complaint-form', [
            'complaintTypes' => DmcaComplaint::complaintTypeLabels(),
        ])->layout('layouts.dmca');
    }
}
