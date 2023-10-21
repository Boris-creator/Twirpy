<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $comment = parent::toArray($request);
        if (! isset($comment['answers_count'])) {
            $comment['answers_count'] = 0;
        }

        return array_merge($comment, [
            'author' => $this->author,
            'answerTo' => $this->answerTo,
            'edited' => $this->edited,
            'owned' => $comment['user_id'] == auth()->id(),
        ]);
    }
}
