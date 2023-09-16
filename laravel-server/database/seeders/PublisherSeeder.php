<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::factory()->createMany([
            [
                'name' => 'O\'Reilly'
            ],
            [
                'name' => 'McMillan & Co'
            ]
        ]);
    }
}