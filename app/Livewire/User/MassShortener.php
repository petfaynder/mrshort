<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\GamificationService;
use App\Services\LinkValidationService;

class MassShortener extends Component
{
    protected $layout = 'components.user-dashboard-layout';

    public $urls = '';
    public $shortenedLinks = [];

    protected $rules = [
        'urls' => 'required',
    ];

    public function render()
    {
        return view('livewire.user.mass-shortener');
    }

    public function shortenUrls()
    {
        $this->validate();

        $urlsArray = array_filter(array_map('trim', explode("\n", $this->urls)));
        $this->shortenedLinks = [];
        
        $validator = app(LinkValidationService::class);
        $massLimit = (int) setting('mass_shrinker_limit', 20);
        
        // Limit URLs
        $urlsArray = array_slice($urlsArray, 0, $massLimit);

        foreach ($urlsArray as $originalUrl) {
            // Auto-add https:// if URL doesn't have a protocol
            if ($originalUrl && !preg_match('#^https?://#i', $originalUrl)) {
                $originalUrl = 'https://' . $originalUrl;
            }
            
            if (filter_var($originalUrl, FILTER_VALIDATE_URL)) {
                // Validate against banned words and domains
                $errors = $validator->validate($originalUrl);
                
                if (!empty($errors)) {
                    $this->shortenedLinks[] = [
                        'original' => $originalUrl,
                        'shortened' => $errors[0],
                    ];
                    continue;
                }
                
                $codeLength = setting('link_code_length', 6);
                $code = Str::random($codeLength);

                $link = Auth::user()->links()->create([
                    'original_url' => $originalUrl,
                    'code' => $code,
                ]);

                // Update gamification goal
                if ($link->user_id) {
                    $gamificationService = app(GamificationService::class);
                    $gamificationService->updateGoalProgress($link->user, 'shorten_links', 1);
                }

                $this->shortenedLinks[] = [
                    'original' => $originalUrl,
                    'shortened' => $link->shortLink(),
                ];
            } else {
                $this->shortenedLinks[] = [
                    'original' => $originalUrl,
                    'shortened' => 'Invalid URL',
                ];
            }
        }

        session()->flash('message', 'URLs successfully shortened.');
    }
}
