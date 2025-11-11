<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdSetting;

class AddAdSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:ad-setting {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new advertisement setting to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        AdSetting::create([
            'key' => $key,
            'value' => $value,
        ]);

        $this->info("Ad setting '{$key}' added successfully.");
    }
}
