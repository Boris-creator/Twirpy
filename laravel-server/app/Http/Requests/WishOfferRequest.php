<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Wish;
use App\Models\WishOffer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class WishOfferRequest extends FormRequest
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
            'book_id' => ['required', 'integer'],
            'wish_id' => ['required', 'integer']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->count()) {
                    return;
                }
                try {
                    Book::findOrFail($this->input('book_id'));
                } catch (ModelNotFoundException $err) {
                    $validator->errors()->add('book_id', 'book not found');
                    return;
                }
                try {
                    Wish::findOrFail($this->input('wish_id'));
                } catch (ModelNotFoundException $err) {
                    $validator->errors()->add('wish_id', 'wish not found');
                    return;
                }
                if (WishOffer::where([
                    'book_id' => $this->input('book_id'),
                    'wish_id' => $this->input('wish_id')
                ])
                ->exists()) {
                    $validator->errors()->add('wish', 'already offered');
                }
            },
        ];
    }
}
