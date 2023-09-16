<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = $request->user()->id;
        $book = parent::toArray($request);
        return array_merge($book, [
            'accessible' => User::query()->find($userId)->accessibleBooks->contains($book['id']),
            'owned' => $book['owner_id'] == $userId,
            'titleThumbnail' => '/thumbnails/' . $book['id'] . '-000.jpg'
        ]);
    }
}
