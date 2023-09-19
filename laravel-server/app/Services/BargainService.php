<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;

class BargainService
{
    public static function isAccessible(int $userId, int $bookId): bool
    {
        $user = User::find($userId);
        return $user->accessibleBooks->contains($bookId);
    }
    public static function hasEnoughBalance(int $userId, int $bookId): bool
    {
        $user = User::find($userId);
        $book = Book::findOrFail($bookId);
        return $user->balance >= $book->price;
    }
    public static function buy(int $userId, int $bookId)
    {
        $user = User::find($userId);
        $book = Book::find($bookId);
        $user->balance -= $book->price;
        $user->accessibleBooks()->attach($bookId);
        $user->save();
        $owner = User::find($book->owner_id);
        $owner->balance += $book->price;
        $owner->save();
        return $book;
    }
}
