<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Wish;
use Illuminate\Http\Request;

class WishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->whenHas('bookId', function ($id) {
            $book = Book::findOrFail($id);
            return Wish::searchByBookFilter($book);
        }, function () {
            return Wish::all();
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Wish
    {
        $wish = new Wish($request->all());
        $wish->user_id = $request->user()->id;
        $wish->save();
        return $wish;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Wish
    {
        return Wish::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
