<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Book;
use App\Services\BookBargainService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BargainController extends Controller
{
    public function buy(Request $request, string $id)
    {
        $book = BookBargainService::buy($request->user()->id, $id);

        return response()->json((new Book($book))->toArray($request), ResponseAlias::HTTP_OK);
    }
}
