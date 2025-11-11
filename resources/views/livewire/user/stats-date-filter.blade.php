<div>
    <select wire:model.live="selectedMonth" class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent text-text-light dark:text-text-dark">
        @foreach($months as $month)
            <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
        @endforeach
    </select>
</div>
