<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageComment
{
    public function handle(Request $request, Closure $next): Response
    {
        $commentId = $request->route()->parameter('comment');
        if (! $commentId) {
            abort(Response::HTTP_BAD_REQUEST);
        }
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id != $request->user()->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
