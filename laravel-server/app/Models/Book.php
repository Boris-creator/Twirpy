<?php

namespace App\Models;

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

    public function publisher(): HasOne
    {
        return $this->HasOne(Publisher::class, 'id', 'published_by');
    }
    public function downloads(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'accessible_books', 'book_id');
    }
}
