<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->createMany([
            [
                'name' => 'Test',
                'email' => 'test@gmail.com',
                'password' => bcrypt('qwerty'),
                'balance' => config('constants.USER_BALANCE'),
            ],
            [
                'name' => 'Test 2',
                'email' => 'test2@gmail.com',
                'password' => bcrypt('qwerty'),
                'balance' => config('constants.USER_BALANCE'),
            ],
        ]);

        $this->call([
            PublisherSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
