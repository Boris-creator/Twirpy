<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;

abstract class BargainService
{
    public static function hasEnoughBalance(int $userId, float $price): bool
    {
        $user = User::find($userId);

        return $user->balance >= $price;
    }

    public static function makeTransaction(User $buyer, User $seller, float $price): void
    {
        $buyer->balance -= $price;
        $buyer->save();
        $seller->balance += $price;
        $seller->save();
    }

    abstract public static function canBeBought(User $buyer, Book $resource): bool;
}
