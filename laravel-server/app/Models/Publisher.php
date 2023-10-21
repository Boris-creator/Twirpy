<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Database\Factories\PublisherFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Publisher
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property-read Book|null $books
 *
 * @method static PublisherFactory factory($count = null, $state = [])
 * @method static Builder|Publisher newModelQuery()
 * @method static Builder|Publisher newQuery()
 * @method static Builder|Publisher query()
 * @method static Builder|Publisher whereCreatedAt($value)
 * @method static Builder|Publisher whereId($value)
 * @method static Builder|Publisher whereName($value)
 * @method static Builder|Publisher whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Publisher extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function books(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id', 'published_by');
    }
}
