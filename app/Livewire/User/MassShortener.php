<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\GamificationService; // Add this line

class MassShortener extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $urls = ''; // Property to hold the input URLs
    public $shortenedLinks = []; // Property to hold the shortened links

    protected $rules = [
        'urls' => 'required', // Basic validation
    ];

    public function render()
    {
        return view('livewire.user.mass-shortener');
    }

    public function shortenUrls()
    {
        $this->validate();

        $urlsArray = array_filter(array_map('trim', explode("\n", $this->urls))); // Split by newline and clean up

        $this->shortenedLinks = []; // Reset the shortened links array

        foreach ($urlsArray as $originalUrl) {
            // Basic URL validation within the loop
            if (filter_var($originalUrl, FILTER_VALIDATE_URL)) {
                $code = Str::random(6); // Generate a random short code

                $link = Auth::user()->links()->create([
                    'original_url' => $originalUrl,
                    'code' => $code,
                ]);

                // Gamification hedefini güncelle
                if ($link->user_id) {
                    $gamificationService = app(GamificationService::class);
                    $gamificationService->updateGoalProgress($link->user, 'shorten_links', 1);
                }

                $this->shortenedLinks[] = [
                    'original' => $originalUrl,
                    'shortened' => $link->shortLink(), // Assuming shortLink method exists on Link model
                ];
            } else {
                // Handle invalid URL - maybe add to a separate error list
                $this->shortenedLinks[] = [
                    'original' => $originalUrl,
                    'shortened' => 'Invalid URL',
                ];
            }
        }

        // Optionally clear the input textarea after shortening
        // $this->urls = '';

        session()->flash('message', 'URLs successfully shortened.');
    }
}
