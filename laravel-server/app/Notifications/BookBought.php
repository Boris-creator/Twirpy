<?php

namespace App\Notifications;

use App\Models\Book;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookBought extends Notification
{
    use Queueable;

    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'price' => $this->book->price,
        ];
    }
}
