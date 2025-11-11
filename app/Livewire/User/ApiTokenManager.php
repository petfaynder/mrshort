<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ApiTokenManager extends Component
{
    protected $layout = 'components.user-dashboard-layout'; // Layout'u belirt

    public $tokens; // Property to hold the user's API tokens
    public $newTokenName = ''; // Property to hold the name for a new token

    protected $rules = [
        'newTokenName' => 'required|string|max:255', // Validation for new token name
    ];

    public function mount()
    {
        $this->loadTokens();
    }

    public function render()
    {
        return view('livewire.user.api-token-manager');
    }

    public function loadTokens()
    {
        $this->tokens = Auth::user()->tokens;
    }

    public function createToken()
    {
        $this->validate();

        $token = Auth::user()->createToken($this->newTokenName);

        // Optionally display the plain text token to the user ONCE
        session()->flash('newToken', $token->plainTextToken);

        $this->newTokenName = ''; // Clear the input field
        $this->loadTokens(); // Refresh the tokens list

        session()->flash('message', 'API Token created successfully.');
    }

    public function deleteToken($tokenId)
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();
        $this->loadTokens(); // Refresh the tokens list
        session()->flash('message', 'API Token deleted successfully.');
    }
}
