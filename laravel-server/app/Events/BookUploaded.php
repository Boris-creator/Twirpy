<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $filename;

    public int $bookId;

    public function __construct(string $filename, int $bookId)
    {
        $this->filename = $filename;
        $this->bookId = $bookId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
