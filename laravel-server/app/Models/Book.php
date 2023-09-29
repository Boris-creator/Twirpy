<?php

namespace App\Models;

use App\Http\Controllers\Api\DTO\BookFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = ['hash_sum'];

    protected $casts = ['published_by' => 'integer'];

    protected $attributes = [
        'price' => 300
    ];

    public static function search(BookFilter $filter)
    {
        return self::when($filter->accessible, function (Builder $query)
        {
            $query->whereHas('downloads', function (Builder $user)
            {
                $user->where('users.id', auth()->id());
            });
        })
            ->when($filter->owned, function (Builder $query)
            {
                $query->whereHas('owner', function (Builder $user)
                {
                    $user->where('users.id', '=', auth()->id());
                });
            }
            )
            ->get();
    }

    public function publisher(): HasOne
    {
        return $this->HasOne(Publisher::class, 'id', 'published_by');
    }

    public function owner(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'owner_id');
    }
    public function downloads(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'accessible_books', 'book_id');
    }
}
