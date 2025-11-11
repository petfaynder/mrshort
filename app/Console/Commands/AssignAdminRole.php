<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-admin-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns the admin role to a specified user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'akartolga0@gmail.com';

        // Import necessary classes
        \App\Models\User::unguard(); // Temporarily unguard the User model
        $user = \App\Models\User::where('email', $email)->first();

        if ($user) {
            // Create the 'admin' role if it doesn't exist
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);

            // Assign the 'admin' role to the user
            $user->assignRole($role);

            $this->info("Admin rolü {$email} kullanıcısına başarıyla atandı.");
        } else {
            $this->error("{$email} e-posta adresine sahip kullanıcı bulunamadı.");
        }
        \App\Models\User::reguard(); // Reguard the User model
    }
}
