<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuickShortener extends Component
{
    public $original_url;
    public $shortenedLink = '';

    protected $rules = [
        'original_url' => 'required|url',
    ];

    public function shortenLink()
    {
        // Auto-add https:// if URL doesn't have a protocol
        if ($this->original_url && !preg_match('#^https?://#i', $this->original_url)) {
            $this->original_url = 'https://' . $this->original_url;
        }
        
        $this->validate();
        
        // Validate URL against banned words, disallowed domains, and safety
        $validator = app(\App\Services\LinkValidationService::class);
        $errors = $validator->validate($this->original_url);
        
        if (!empty($errors)) {
            $this->addError('original_url', $errors[0]);
            return;
        }
        
        // Check URL safety if enabled
        $safetyErrors = $validator->checkUrlSafety($this->original_url);
        if (!empty($safetyErrors)) {
            $this->addError('original_url', $safetyErrors[0]);
            return;
        }

        $codeLength = setting('link_code_length', 6);
        $code = Str::random($codeLength);

        $link = Auth::user()->links()->create([
            'original_url' => $this->original_url,
            'code' => $code,
        ]);

        $this->original_url = '';
        $this->shortenedLink = $link->shortLink();
        
        $this->dispatch('linkShortened');
    }

    public function render()
    {
        return view('livewire.user.quick-shortener');
    }
}

