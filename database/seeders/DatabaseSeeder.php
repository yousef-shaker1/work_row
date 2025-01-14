<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\CountryGovernorateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@mail.com',
        //     'password' => Hash::make('password'),
        //     'type' => 'admin',
        // ]);


        // User::create([
        //     'name' => 'employee',
        //     'email' => 'employee@mail.com',
        //     'password' => Hash::make('password'),
        //     'type' => 'employee',
        // ]);


        $this->call([
            CountryGovernorateSeeder::class,
        ]);
    }
}
