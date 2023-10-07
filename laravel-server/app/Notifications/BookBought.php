<?php

namespace App\Notifications;

use App\Models\Book;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookBought extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'price' => $this->book->price,
        ];
    }
}
