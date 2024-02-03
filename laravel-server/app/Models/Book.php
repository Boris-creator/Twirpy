<?php

namespace App\Models;

use App\Http\Controllers\Api\DTO\BookFilter;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\BookResource
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $title
 * @property string|null $year
 * @property string|null $city
 * @property string|null $volume
 * @property string|null $annotation
 * @property string|null $isbn
 * @property string|null $pages
 * @property int|null $language_id
 * @property int|null $published_by
 * @property float $price
 * @property string|null $filename
 * @property string|null $hash_sum
 * @property int|null $owner_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $downloads
 * @property-read int|null $downloads_count
 * @property-read User|null $owner
 * @property-read Publisher|null $publisher
 *
 * @method static Builder|Book newModelQuery()
 * @method static Builder|Book newQuery()
 * @method static Builder|Book query()
 * @method static Builder|Book whereAnnotation($value)
 * @method static Builder|Book whereCity($value)
 * @method static Builder|Book whereCreatedAt($value)
 * @method static Builder|Book whereFilename($value)
 * @method static Builder|Book whereHashSum($value)
 * @method static Builder|Book whereId($value)
 * @method static Builder|Book whereIsbn($value)
 * @method static Builder|Book whereLanguageId($value)
 * @method static Builder|Book whereOwnerId($value)
 * @method static Builder|Book wherePages($value)
 * @method static Builder|Book wherePrice($value)
 * @method static Builder|Book wherePublishedBy($value)
 * @method static Builder|Book whereTitle($value)
 * @method static Builder|Book whereUpdatedAt($value)
 * @method static Builder|Book whereVolume($value)
 * @method static Builder|Book whereYear($value)
 *
 * @mixin Eloquent
 */
class Book extends Model
{
    use HasFactory;

    protected $guarded = ['owner_id'];

    protected $hidden = ['hash_sum'];

    protected $casts = ['published_by' => 'integer'];

    protected $attributes = [
        'price' => 300,
    ];

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
