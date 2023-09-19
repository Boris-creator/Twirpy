<?php

namespace App\Http\Middleware;

use App\Services\BargainService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookCanBeBought
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()->id;
        $bookId = $request->route()->parameters['id'];
        if (BargainService::isAccessible($userId, $bookId)) {
            abort(Response::HTTP_BAD_REQUEST, 'already bought');
        }
        if (!BargainService::hasEnoughBalance($userId, $bookId)) {
            abort(Response::HTTP_BAD_REQUEST, 'you are too poor');
        }
        return $next($request);
    }
}
