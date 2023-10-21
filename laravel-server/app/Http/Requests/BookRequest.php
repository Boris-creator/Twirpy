<?php

namespace App\Http\Requests;

use App\Models\Book;
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
            'file' => ['required', 'file', 'max:800000', 'mimes:pdf'],
            'isbn' => ['nullable', 'string', 'max:13', 'min:10'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('file')) {
                    return;
                }

                $hash = sha1_file($this->file('file'));
                if (Book::query()->where('hash_sum', '=', $hash)->exists()) {
                    $validator->errors()->add('file', 'already exists');
                }
            },
        ];
    }
}
