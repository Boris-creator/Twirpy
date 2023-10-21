<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        Publisher::factory()->createMany([
            [
                'name' => 'O\'Reilly',
            ],
            [
                'name' => 'McMillan & Co',
            ],
        ]);
    }
}
