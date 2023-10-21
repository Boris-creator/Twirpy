<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $text
 * @property int|null $answer_to
 * @property int $book_id
 * @property int $user_id
 * @property-read Comment|null $answerTo
 * @property-read Collection<int, Comment> $answers
 * @property-read int|null $answers_count
 * @property-read User|null $author
 * @property-read bool $edited
 * @property-read mixed $related
 * @property-read Collection<int, Comment> $relatedComments
 * @property-read int|null $related_comments_count
 *
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAnswerTo($value)
 * @method static Builder|Comment whereBookId($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereText($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 *
 * @mixin Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static $snakeAttributes = false;

    public function answerTo(): BelongsTo
    {
        return $this->BelongsTo(Comment::class, 'answer_to', 'id');
    }

    public function answers(): HasMany
    {
        return $this->HasMany(Comment::class, 'answer_to', 'id');
    }

    public function author(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'user_id')->select(['id', 'name']);
    }

    public function relatedComments(): HasMany
    {
        return $this->HasMany(Comment::class, 'answer_to', 'id')
            ->with('relatedComments');
    }

    public function getEditedAttribute(): bool
    {
        return $this->created_at != $this->updated_at;
    }

    public function getRelatedAttribute(): array
    {
        return $this->flatTreeViewToArray();
    }

    public static function withRelations(): Builder
    {
        return self::query()
            ->with('author')
            ->withCount('answers')
            ->with(['answerTo' => function (Builder $query) {
                return $query->with('author')->select(['id', 'text', 'user_id']);
            }]);
    }

    private function flatTreeViewToArray(): array
    {
        $answers = [];
        foreach ($this->relatedComments as $comment) {
            $answers[] = $comment;
            $answers = array_merge($answers, $comment->flatTreeViewToArray());
        }

        return $answers;
    }
}
