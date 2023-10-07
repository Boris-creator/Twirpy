<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookIsAccessible
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $params = $request->route()->parameters();
        if (! array_key_exists('id', $params)) {
            abort(Response::HTTP_BAD_REQUEST);
        }
        $user = User::query()->findOrFail($request->user()->id);
        if (! $user->accessibleBooks()->find($params['id'])) {
            abort(Response::HTTP_FORBIDDEN, 'You must buy this book if want to download it.');
        }

        return $next($request);
    }
}
