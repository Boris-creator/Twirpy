<?php

namespace App\Listeners;

use App\Events\BookUploaded;
use App\Models\Book;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookUploadSubscription
{
    public function __construct()
    {
        //
    }

    public function handle(BookUploaded $event): void
    {
        $path = storage_path() . '/app/books/';
        $extract_path = storage_path() . '/app/public/thumbnails';
        $pdf = $path.$event->filename;
        $command = sprintf('pdfimages -j -f 1 -l 1 %s %s/%d', $pdf, $extract_path, $event->bookId);
        exec($command);
    }
}
