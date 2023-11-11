<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookIsAccessible
{
    public function handle(Request $request, Closure $next): Response
    {
        $params = $request->route()->parameters();
        if (! array_key_exists('id', $params)) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        $user = User::findOrFail($request->user()->id);
        if ($user->hasPermission(Permission::DOWNLOAD_FREE)) {
            return $next($request);
        }
        if (! $user->accessibleBooks()->find($params['id'])) {
            abort(Response::HTTP_FORBIDDEN, 'You must buy this book if want to download it.');
        }

        return $next($request);
    }
}
