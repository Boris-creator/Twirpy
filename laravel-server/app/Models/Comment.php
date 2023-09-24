<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function getRelatedAttribute()
    {
        return $this->flatTreeViewToArray();
    }

    public static function withRelations()
    {
        return self::query()
            ->with('author')
            ->withCount('answers')
            ->with(['answerTo' => function($query)
            {
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
