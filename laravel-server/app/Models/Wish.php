<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Wish extends Model
{
    use HasFactory;

    protected $guarded = ['user_id'];

    public static function searchByBookFilter(Book $book)
    {
        return self::where('user_id', '!=', $book->owner_id)
            ->where('title', 'LIKE', '%'.$book->title.'%')
            ->when(isset($book->published_by), function (Builder $query) use($book) {
                return $query->where('published_by', $book->published_by);
            })
            ->get();
    }
}
