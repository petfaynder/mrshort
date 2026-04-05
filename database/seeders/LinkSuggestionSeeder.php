<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LinkSuggestion;

class LinkSuggestionSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if table is empty
        if (LinkSuggestion::count() > 0) {
            $this->command->info('LinkSuggestion table already has data — skipping seed.');
            return;
        }

        $suggestions = config('link_suggestions', []);

        if (empty($suggestions)) {
            $this->command->warn('No suggestions found in config/link_suggestions.php');
            return;
        }

        foreach ($suggestions as $index => $item) {
            LinkSuggestion::create([
                'icon'       => $item['icon'],
                'color'      => $item['color'],
                'title'      => $item['title'],
                'text'       => $item['text'],
                'is_active'  => true,
                'sort_order' => $index,
            ]);
        }

        $this->command->info('Seeded ' . count($suggestions) . ' link suggestions.');
    }
}
