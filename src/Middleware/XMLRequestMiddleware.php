<?php

namespace Zirsakht\Responder\Middleware;

use Closure;
use Illuminate\Http\Request;

class XMLRequestMiddleware
{
    /**
     * Merge the converted xml array into the request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->merge($request->xml());

        return $next($request);
    }
}
