<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations and seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $executionStart = now();

        Artisan::call('migrate:refresh --seed');
        Artisan::call('queue:work');
        File::cleanDirectory(storage_path() . '/app/books');
        File::cleanDirectory(storage_path() . '/app/public/thumbnails');
        File::put(storage_path() . '/app/books/.gitkeep', '');
        File::put(storage_path() . '/app/public/thumbnails/.gitkeep', '');

        $executionFinish = $executionStart->diffInMilliseconds(now());
        $this->info(sprintf('All done in %d ms!', $executionFinish));
    }
}
