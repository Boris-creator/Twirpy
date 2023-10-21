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

    public function __construct(public Book $book)
    {
        //
    }

    public function handle(BookService $service): void
    {
        $service->fetchBibliography($this->book);
    }
}
