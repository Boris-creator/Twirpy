<?php

namespace App\Http\Requests;

use App\Models\Book;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookSaveRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'id' => ['nullable', 'integer', 'exists:books'],
            'publishedBy.id' => ['nullable', 'integer', 'exists:publishers,id'],
            'isbn' => ['nullable', 'string', 'regex:/^\d{10}(\d{3})?$/'],
            'file' => [
                Rule::requiredIf(fn() => !empty($this->route('id'))),
                'file', 'max:800000', 'mimes:pdf',
                function (string $attribute, mixed $file, Closure $fail): void {
                    if ($this->validator->errors()->has('file')) {
                        return;
                    }
                    $hash = sha1_file($file);
                    if (Book::whereHashSum($hash)->exists()) {
                        $fail("{$attribute} already exists.");
                    }
                },
            ],
        ];
    }
}
