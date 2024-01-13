<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\DTO\BookFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookSaveRequest;
use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BooksController extends Controller
{
    public function index(): array {
        $filter = new BookFilter();
        $filter->accessible = request()->boolean('accessible');
        $filter->owned = request()->boolean('owned');

        $books = Book::search($filter);

        return \App\Http\Resources\Book::collection($books)->toArray(\request());
    }

    public function store(BookSaveRequest $request): Book {
        $userId = $request->user()->id;

        $publisherId = BookService::selectOrCreatePublisher($request);
        $newBook = Book::create(array_merge(
            $request->only(['title', 'isbn']),
            [
                'owner_id' => $userId,
                'published_by' => $publisherId,
            ]
        ));
        User::query()->find($userId)->accessibleBooks()->attach($newBook->id);

        $file = $request->file('file');

        return BookService::upload($newBook, $file);
    }

    public function show(string $id): array {
        return (new \App\Http\Resources\Book(Book::with('publisher')->withCount('downloads')->findOrFail($id)))->toArray(\request());
    }

    public function update(BookSaveRequest $request, string $id): array {
        $publisherId = BookService::selectOrCreatePublisher($request);
        $existingBook = Book::query()->findOrFail($id);
        $existingBook->fill(array_merge(
            $request->only(['title', 'isbn']),
            [
                'published_by' => $publisherId,
            ]
        ));
        $existingBook->save();

        return (new \App\Http\Resources\Book($existingBook))->toArray($request);
    }

    public function destroy(string $id) {
        //
    }

    public function download(string $id): StreamedResponse {
        $book = Book::query()->findOrFail($id);
        if(!isset($book->filename)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Storage::download('books/' . $book->filename);
    }
}
