<?php
namespace App\Http\Middleware;
use Closure;
use Response;

class CheckQueryString {
    public function __construct()
    {

    }

    public function handle($request, Closure $next)
    {
        if($request->query('password') == 'zaqwsx') {
            return $next($request);
        }

        return Response::make('Niepoprawne hasło', 401);
    }
}