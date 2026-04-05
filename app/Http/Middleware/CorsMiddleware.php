<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = config('cors.allowed_origins', [
            'http://localhost:5173',
            'http://localhost:3000',
            'http://localhost:8080',
        ]);

        $origin = $request->headers->get('origin');

        // Check if origin matches allowed origins or patterns
        $isAllowed = in_array($origin, $allowedOrigins);
        
        if (!$isAllowed) {
            foreach (config('cors.allowed_origins_patterns', []) as $pattern) {
                if (preg_match($pattern, $origin)) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        if ($isAllowed || $origin === null) {
            $response = $next($request);

            if ($origin) {
                $response->header('Access-Control-Allow-Origin', $origin);
            }
            
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH');
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
            $response->header('Access-Control-Expose-Headers', 'Authorization');
            $response->header('Access-Control-Allow-Credentials', 'true');
            $response->header('Access-Control-Max-Age', '86400');

            return $response;
        }

        return response('Forbidden', 403);
    }
}
