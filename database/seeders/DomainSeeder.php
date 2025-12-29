<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default domain from APP_URL
        $appUrl = config('app.url', 'http://localhost');
        $parsedUrl = parse_url($appUrl);
        
        $domain = $parsedUrl['host'] ?? 'localhost';
        $protocol = $parsedUrl['scheme'] ?? 'https';
        
        // Handle localhost with port
        if (isset($parsedUrl['port'])) {
            $domain .= ':' . $parsedUrl['port'];
        }
        
        Domain::firstOrCreate(
            ['domain' => $domain],
            [
                'name' => 'Default',
                'protocol' => $protocol,
                'is_active' => true,
            ]
        );
        
        $this->command->info('Default domain created: ' . $protocol . '://' . $domain);
    }
}
