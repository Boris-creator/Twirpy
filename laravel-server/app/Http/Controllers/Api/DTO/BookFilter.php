<?php

namespace App\Http\Controllers\Api\DTO;

use Illuminate\Database\Eloquent\Builder;

class BookFilter
{
    public ?bool $accessible;

    public ?bool $owned;

    public function apply(Builder $query, int $userId): Builder
    {
        $query->when($this->accessible, function (Builder $query) {
            $query->whereHas('downloads', function (Builder $user) {
                $user->where('users.id', auth()->id());
            });
        })
            ->when($this->owned, function (Builder $query) use ($userId) {
                $query->whereHas('owner', function (Builder $user) use ($userId) {
                    $user->where('users.id', '=', $userId);
                });
            }
            );

        return $query;
    }
}
