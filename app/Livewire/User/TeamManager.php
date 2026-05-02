<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamInvite;
use Illuminate\Support\Facades\Auth;

class TeamManager extends Component
{
    public ?Team $myTeam = null;
    public ?TeamMember $myMembership = null;
    public $teams = [];
    public $pendingInvites = [];
    
    public bool $showCreateModal = false;
    public bool $showJoinModal = false;
    
    public string $newTeamName = '';
    public string $newTeamDescription = '';
    public bool $newTeamIsPublic = true;
    
    public string $searchQuery = '';

    protected $rules = [
        'newTeamName' => 'required|min:3|max:50|unique:teams,name',
        'newTeamDescription' => 'max:500',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $userId = Auth::id();
        
        // Check if user is in a team
        $this->myMembership = TeamMember::where('user_id', $userId)->with('team')->first();
        $this->myTeam = $this->myMembership?->team;
        
        // Get pending invites
        $this->pendingInvites = TeamInvite::getPendingForUser($userId)->toArray();
        
        // Get public teams for browsing
        if (!$this->myTeam) {
            $this->teams = Team::where('is_public', true)
                ->where('is_active', true)
                ->where('member_count', '<', 20)
                ->orderBy('weekly_points', 'desc')
                ->limit(20)
                ->get()
                ->toArray();
        }
    }

    public function createTeam()
    {
        $this->validate();
        
        $user = Auth::user();
        $creationCost = 1000;
        
        // Check if user has enough points
        if ($user->gamification_points < $creationCost) {
            $this->addError('newTeamName', 'At least ' . $creationCost . ' points are required to create a team.');
            return;
        }
        
        // Deduct points
        $user->decrement('gamification_points', $creationCost);
        
        // Create team
        $team = Team::create([
            'name' => $this->newTeamName,
            'description' => $this->newTeamDescription,
            'leader_id' => $user->id,
            'is_public' => $this->newTeamIsPublic,
        ]);
        
        // Add user as leader
        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'leader',
        ]);
        
        $this->showCreateModal = false;
        $this->reset(['newTeamName', 'newTeamDescription', 'newTeamIsPublic']);
        $this->loadData();
        
        $this->dispatch('team-created');
    }

    public function joinTeam(int $teamId)
    {
        $team = Team::find($teamId);
        
        if (!$team || $team->member_count >= 20) {
            return;
        }
        
        TeamMember::create([
            'team_id' => $teamId,
            'user_id' => Auth::id(),
            'role' => 'member',
        ]);
        
        $team->updateMemberCount();
        
        $this->loadData();
        $this->dispatch('team-joined');
    }

    public function leaveTeam()
    {
        if (!$this->myMembership || !$this->myTeam) {
            return;
        }
        
        // Leader cannot leave, must transfer leadership first
        if ($this->myMembership->role === 'leader') {
            $this->dispatch('cannot-leave-as-leader');
            return;
        }
        
        $team = $this->myTeam;
        $this->myMembership->delete();
        $team->updateMemberCount();
        
        $this->loadData();
        $this->dispatch('team-left');
    }

    public function acceptInvite(int $inviteId)
    {
        $invite = TeamInvite::find($inviteId);
        
        if ($invite && $invite->user_id === Auth::id()) {
            $invite->accept();
            $this->loadData();
        }
    }

    public function rejectInvite(int $inviteId)
    {
        $invite = TeamInvite::find($inviteId);
        
        if ($invite && $invite->user_id === Auth::id()) {
            $invite->reject();
            $this->loadData();
        }
    }

    public function searchTeams()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->loadData();
            return;
        }

        // Escape LIKE wildcards (% and _) to prevent unintended pattern matching
        $escaped = addcslashes($this->searchQuery, '%_');

        $this->teams = Team::where('is_public', true)
            ->where('is_active', true)
            ->where('name', 'like', '%' . $escaped . '%')
            ->orderBy('weekly_points', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }

    // Chat properties
    public $chatMessages = [];
    public string $newMessage = '';
    public bool $showChat = false;

    public function toggleChat()
    {
        $this->showChat = !$this->showChat;
        if ($this->showChat && $this->myTeam) {
            $this->loadMessages();
        }
    }

    public function loadMessages()
    {
        if (!$this->myTeam) return;
        
        $this->chatMessages = $this->myTeam->messages()
            ->with('user:id,name,avatar')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function sendMessage()
    {
        if (!$this->myTeam || empty(trim($this->newMessage))) return;
        
        if (strlen($this->newMessage) > 200) {
            $this->addError('newMessage', 'Message can be at most 200 characters');
            return;
        }

        \App\Models\TeamMessage::create([
            'team_id' => $this->myTeam->id,
            'user_id' => Auth::id(),
            'message' => trim($this->newMessage),
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        
        // Dispatch event for scroll to bottom
        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.user.team-manager')
            ->layout('components.user-dashboard-layout');
    }
}

