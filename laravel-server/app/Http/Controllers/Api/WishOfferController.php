<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WishOfferRequest;
use App\Models\User;
use App\Models\Wish;
use App\Models\WishOffer;
use App\Notifications\WishOfferOpened;

class WishOfferController extends Controller
{
    public function offer(WishOfferRequest $request)
    {
        $offer = new WishOffer(array_merge($request->all(), [
            'user_id' => auth()->id(),
        ]));
        $offer->status = 'opened';
        $offer->save();
        $wish = Wish::find($offer->wish_id);
        User::find($wish->user_id)->notify(new WishOfferOpened($offer->id));

        return $offer;
    }
}
