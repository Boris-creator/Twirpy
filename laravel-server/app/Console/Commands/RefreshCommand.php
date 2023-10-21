<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class RefreshCommand extends Command
{
    protected $signature = 'refresh';

    protected $description = 'Run migrations and seeders';

    public function handle(): void
    {
        $executionStart = now();

        Artisan::call('migrate:refresh --seed');
        // Artisan::call('queue:work');
        File::cleanDirectory(storage_path().'/app/books');
        File::cleanDirectory(storage_path().'/app/public/thumbnails');
        File::put(storage_path().'/app/books/.gitkeep', '');
        File::put(storage_path().'/app/public/thumbnails/.gitkeep', '');

        $executionFinish = $executionStart->diffInMilliseconds(now());
        $this->info(sprintf('All done in %d ms!', $executionFinish));
    }
}
