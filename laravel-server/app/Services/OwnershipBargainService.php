<?php

namespace App\Services;

use App\Models\BargainOwnership;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OwnershipBargainService extends BargainService
{
    public static function closeBargain(BargainOwnership $bargain): void
    {
        DB::transaction(function () use($bargain)
        {
            $book = $bargain->book;
            $book->owner()->save($bargain->buyer);
            $bargain->update(['done' => true]);
            self::makeTransaction($bargain->buyer, $bargain->seller, $bargain->price);
        });
    }

    public static function canBeBought(User $buyer, Book $resource): bool
    {
        return $resource->owner()->id !== $buyer->id;
    }
}
