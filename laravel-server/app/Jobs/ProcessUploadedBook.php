<?php

namespace App\Jobs;

use App\Models\Book;
use App\Services\BookService;
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
    public function handle(BookService $service): void
    {
        $service->fetchBibliography($this->book);
    }
}
