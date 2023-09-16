<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'publishedBy.id' => ['nullable', 'integer'],
            //...
            'file' => ['required', 'file', 'max:800000', 'mimes:pdf']
        ];
    }

    public function after(): array
    {
        return [
            function(Validator $validator)
            {
                if ($validator->errors()->has('file'))
                {
                    return;
                }

                $hash = sha1_file($this->file('file'));
                if (Book::query()->where('hash_sum', '=', $hash)->exists())
                {
                   $validator->errors()->add('file', 'already exists');
                };
            }
        ];
    }
}
