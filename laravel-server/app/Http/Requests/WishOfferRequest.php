<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Wish;
use App\Models\WishOffer;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class WishOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', 'exists:'.app(Book::class)->getTable().',id'],
            'wish_id' => [
                'required', 'integer', 'exists:'.app(Wish::class)->getTable().',id',
                function (string $attribute, mixed $id, Closure $fail): void {
                    if ($this->validator->errors()->count()) {
                        return;
                    }
                    if (WishOffer::where([
                        'book_id' => $this->input('book_id'),
                        'wish_id' => $this->input('wish_id'),
                    ])
                        ->exists()) {
                        $fail('already offered');
                    }
                },
            ],
        ];
    }
}
