<?php

namespace App\Listeners;

use App\Events\BookUploaded;
use App\Services\BookService;

class BookUploadSubscription
{
    public function __construct()
    {
        //
    }

    public function handle(BookUploaded $event): void
    {
        BookService::storeBookCover($event->filename, $event->bookId);
    }
}
