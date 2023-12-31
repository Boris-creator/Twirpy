<?php

namespace App\Http\Requests;

use App\Models\Book;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'publishedBy.id' => ['nullable', 'integer'],
            //...
            'file' => [
                'required', 'file', 'max:800000', 'mimes:pdf',
                function (string $attribute, mixed $file, Closure $fail): void {
                    $hash = sha1_file($file);
                    if (Book::whereHashSum($hash)->exists()) {
                        $fail("{$attribute} already exists.");
                    }
                },
            ],
            'isbn' => ['nullable', 'string', 'max:13', 'min:10'],
        ];
    }
}
