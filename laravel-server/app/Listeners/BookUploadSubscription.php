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

        $book = Book::query()->find($event->bookId);
        if (!array_key_exists('isbn', $book->toArray()))
        {
            return;
        }

        $fetch = curl_init(sprintf('https://openlibrary.org/isbn/%s.json', $book->isbn));
        curl_setopt($fetch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($fetch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($fetch);
        if (curl_error($fetch)) {
            logger(curl_error($fetch));
        }
        curl_close($fetch);
        $book_data = json_decode($res, true);
        if (isset($book_data['publish_date']) && !isset($book->year))
        {
            $book->year = $book_data['publish_date'];
            $book->save();
        }
    }
}
