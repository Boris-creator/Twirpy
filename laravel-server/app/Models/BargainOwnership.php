<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BargainOwnership extends Model
{
    use HasFactory;

    protected $guarded = ['done'];

    public function book(): HasOne
    {
        return $this->HasOne(Book::class);
    }

    public function buyer(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'buyer_id');
    }

    public function seller(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'seller_id');
    }
}
