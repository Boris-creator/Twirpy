<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use App\Notifications\BookBought;
use Illuminate\Support\Facades\DB;

class BookBargainService extends BargainService
{
    public static function canBeBought(User $buyer, Book $book): bool
    {
        return ! $buyer->accessibleBooks->contains($book);
    }

    public static function buy(int $userId, int $bookId)
    {
        $user = User::find($userId);
        $book = Book::find($bookId);
        DB::transaction(function () use ($user, $book, $bookId) {
            $user->accessibleBooks()->attach($bookId);
            $owner = User::find($book->owner_id);
            self::makeTransaction($user, $owner, $book->price);
            $owner->notify(new BookBought($user, $book));
        });

        return $book;
    }
}
