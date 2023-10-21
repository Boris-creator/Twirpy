<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bookId = $this->route()->parameters()['book'];
        $userId = $this->user()->id;

        return $userId == Book::find($bookId)->owner_id;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'publishedBy.id' => ['nullable', 'integer'],
            'isbn' => ['nullable', 'string'],
        ];
    }
}
