<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $bookId = $this->route()->parameters()['book'];
        $userId = $this->user()->id;

        return $userId == Book::find($bookId)->owner_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'publishedBy.id' => ['nullable', 'integer'],
            'isbn' => ['nullable', 'string'],
        ];
    }
}
