<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUploadedBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Book $book)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fetch = curl_init(sprintf('https://openlibrary.org/isbn/%s.json', $this->book->isbn));
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
            $this->book->year = $book_data['publish_date'];
            $this->book->save();
        }
    }
}
