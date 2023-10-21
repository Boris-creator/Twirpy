<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\BargainOwnership
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $buyer_id
 * @property int $seller_id
 * @property int $book_id
 * @property float $price
 * @property int $done
 * @property-read Book|null $book
 * @property-read User|null $buyer
 * @property-read User|null $seller
 *
 * @method static Builder|BargainOwnership newModelQuery()
 * @method static Builder|BargainOwnership newQuery()
 * @method static Builder|BargainOwnership query()
 * @method static Builder|BargainOwnership whereBookId($value)
 * @method static Builder|BargainOwnership whereBuyerId($value)
 * @method static Builder|BargainOwnership whereCreatedAt($value)
 * @method static Builder|BargainOwnership whereDone($value)
 * @method static Builder|BargainOwnership whereId($value)
 * @method static Builder|BargainOwnership wherePrice($value)
 * @method static Builder|BargainOwnership whereSellerId($value)
 * @method static Builder|BargainOwnership whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
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
