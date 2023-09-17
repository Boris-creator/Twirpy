<?php

namespace App\Http\Controllers\Api;

use App\Events\BookUploaded;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends Controller
{
    public function index()
    {
       return \App\Http\Resources\Book::collection(Book::all())->toArray(\request());
    }

    public function store(BookRequest $request)
    {
        $userId = $request->user()->id;

        $publisherId = $this->selectOrCreatePublisher($request);
        $newBook = Book::query()->create(array_merge(
            $request->only(['title', 'isbn']),
            [
                'owner_id' => $userId,
                'published_by' => $publisherId
            ]
        ));
        User::query()->find($userId)->accessibleBooks()->attach($newBook->id);

        $file = $request->file('file');
        $file_name = $newBook->id . '.' . $file->getClientOriginalExtension();
        $newBook->filename = $file_name;

        //$file->storeAs('books', $file_name); //#1
        Storage::disk('local')->put('books' . DIRECTORY_SEPARATOR . $file_name, file_get_contents($file)); //#2

        $newBook->hash_sum = sha1_file(storage_path() . '/app/books/' . $file_name);

        $newBook->save();
        \event(new BookUploaded($file_name, $newBook->id));
        return $newBook;
    }

    public function show(string $id)
    {
        return (new \App\Http\Resources\Book(Book::with('publisher')->withCount('downloads')->findOrFail($id)))->toArray(\request());
    }

    public function update(BookUpdateRequest $request, string $id)
    {
        $publisherId = $this->selectOrCreatePublisher($request);
        $existingBook = Book::query()->findOrFail($id);
        $existingBook->fill(array_merge(
            $request->only(['title', 'isbn']),
            [
                'published_by' => $publisherId
            ]
        ));
        $existingBook->save();
        return $existingBook;
    }

    public function destroy(string $id)
    {
        //
    }

    public function download(string $id)
    {
        $book = Book::query()->findOrFail($id);
        if (!isset($book->filename))
        {
            abort(Response::HTTP_NOT_FOUND);
        }
        return Storage::download('books/' . $book->filename);
    }

    private function selectOrCreatePublisher(FormRequest $request)
    {
        $publisherId = $request->input('publisher.id');
        if (!isset($publisherId) && $request->has('publisher.name'))
        {
            global $publisherId;
            $newPublisher = Publisher::query()->create([
                'name' => $request->input('publisher.name')
            ]);
            $publisherId = $newPublisher->id;
        }
        return $publisherId;
    }
}
