<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\request()->has('answerTo')) {
            $comments = Comment::find(\request()->input('answerTo'))->related;
            return CommentResource::collection($comments)->toArray(\request());
        }
        $comments = Comment::withRelations()
            ->when(\request()->has('bookId'), function (Builder $query) {
               return $query->where(['book_id' => \request()->input('bookId')]);
            })
            ->get();
        return CommentResource::collection($comments)->toArray(\request());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newComment = Comment::create([
            'text' => $request->input('text'),
            'book_id' => $request->input('subject.id'),
            'user_id' => auth()->id(),
            'answer_to' => $request->input('answerTo.id'),
        ]);

        return (new CommentResource($newComment))->toArray($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Comment::withRelations()->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment =  Comment::findOrFail($id);
        $comment->fill($request->only(['text']));
        $comment->save();
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): bool
    {
        return Comment::destroy($id) > 0;
    }
}
