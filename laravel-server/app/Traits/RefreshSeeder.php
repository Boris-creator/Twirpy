<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait RefreshSeeder
{
    protected function getModel(): ?string
    {
        return null;
    }

    public function refresh(Collection $items, string $uniqueKey, string $relation = null, string $relationUniqueKey = null): void
    {
        /** @var Model $model */
        $model = $this->getModel();

        $existingItems = $model::query()->when(isset($relation), function (Builder $query) use ($relation) {
            $query->with([$relation]);
        })->get();
        $itemsToDelete = $model::whereNotIn(
            $uniqueKey,
            $items->map(function ($item) use ($uniqueKey): string {
                return ((object) $item)->$uniqueKey;
            })
        );
        $itemsToCreate = $items->whereNotIn(
            $uniqueKey,
            $existingItems->pluck($uniqueKey)
        )->map(function ($item) use ($relation) {
            return Arr::except(clone $item, $relation);
        });
        $itemsToUpdate = $existingItems->whereNotIn(
            'id',
            $itemsToDelete->pluck('id')
        );

        if (isset($relation)) {
            $itemsToDelete->each(function (Model $item) use ($relation): void {
                $item->$relation()->detach();
            });
        }
        $itemsToDelete->delete();
        $model::insert($itemsToCreate->toArray());

        if (! isset($relation)) {
            return;
        }

        /** @var Model $relationModel */
        $relationModel = (new $model())->$relation()->getRelated();
        $createdItems = $model::whereIn(
            $uniqueKey,
            $itemsToCreate->pluck($uniqueKey)
        )->get();
        $createdItems
            ->merge($itemsToUpdate)
            ->each(function ($item) use ($relation, $items, $uniqueKey, $relationUniqueKey, $relationModel) {
                $related = $relationModel::whereIn(
                    $relationUniqueKey,
                    $items->firstWhere($uniqueKey, $item->$uniqueKey)->$relation->pluck($relationUniqueKey)
                )->get('id')->pluck('id');

                $item->$relation()->sync($related);
            });
    }
}
