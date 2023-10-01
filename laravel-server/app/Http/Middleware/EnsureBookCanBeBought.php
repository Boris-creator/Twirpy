<?php

namespace App\Http\Middleware;

use App\Models\Book;
use App\Models\User;
use App\Services\BookBargainService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookCanBeBought
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()->id;
        $bookId = $request->route()->parameters['id'];
        $book = Book::findOrFail($bookId);

        if (!BookBargainService::canBeBought(User::findOrFail($userId), $book)) {
            abort(Response::HTTP_BAD_REQUEST, 'already bought');
        }
        if (!BookBargainService::hasEnoughBalance($userId, $book->price)) {
            abort(Response::HTTP_BAD_REQUEST, 'you are too poor');
        }
        return $next($request);
    }
}
