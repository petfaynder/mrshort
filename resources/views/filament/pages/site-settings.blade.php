<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        
        <div class="flex justify-end mt-6">
            <x-filament::button type="submit" icon="heroicon-o-check" size="lg">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
