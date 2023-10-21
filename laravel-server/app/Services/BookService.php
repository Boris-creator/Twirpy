<?php

namespace App\Services;

use App\Events\BookUploaded;
use App\Jobs\ProcessUploadedBook;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookService
{
    public static function upload(Book $newBook, UploadedFile $file): Book
    {
        $file_name = $newBook->id.'.'.$file->getClientOriginalExtension();
        $newBook->filename = $file_name;

        //$file->storeAs('books', $file_name); //#1
        Storage::disk('local')->put('books'.DIRECTORY_SEPARATOR.$file_name, file_get_contents($file)); //#2

        $newBook->hash_sum = sha1_file(storage_path().'/app/books/'.$file_name);

        $newBook->save();
        \event(new BookUploaded($file_name, $newBook->id)); //TODO: separate to a job too.
        ProcessUploadedBook::dispatchIf(array_key_exists('isbn', $newBook->toArray()), $newBook);

        return $newBook;
    }

    public function fetchBibliography(Book $book): void
    {
        $fetch = curl_init(sprintf('https://openlibrary.org/isbn/%s.json', $book->isbn));
        curl_setopt($fetch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($fetch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($fetch);
        if (curl_error($fetch)) {
            logger(curl_error($fetch));
        }
        curl_close($fetch);
        $book_data = json_decode($res, true);
        if (isset($book_data['publish_date']) && ! isset($book->year)) {
            $book->year = $book_data['publish_date'];
            $book->save();
        }
    }

    public static function selectOrCreatePublisher(FormRequest $request)
    {
        $publisherId = $request->input('publisher.id');
        if (! isset($publisherId) && $request->has('publisher.name')) {
            global $publisherId;
            $newPublisher = Publisher::query()->create([
                'name' => $request->input('publisher.name'),
            ]);
            $publisherId = $newPublisher->id;
        }

        return $publisherId;
    }
}
