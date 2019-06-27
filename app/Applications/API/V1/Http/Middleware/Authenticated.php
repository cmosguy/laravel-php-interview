<?php


namespace V1\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use Illuminate\Http\Request;
use V1\Exceptions\ApiKeyIsNotSet;

class Authenticated
{
    /**
     * @param Closure $next
     * @param Request $request
     * @return IlluminateJsonResponse
     * @throws ApiKeyIsNotSet
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): IlluminateJsonResponse
    {
        $this->validate($apiKey = env('API_KEY'));
        $value = $request->headers->get('authorization');

        if ($value !== "Bearer {$apiKey}") {
            throw new AuthenticationException();
        }

        return $next($request);
    }

    /**
     * @param null|string $apiKey
     * @throws ApiKeyIsNotSet
     */
    private function validate(?string $apiKey): void
    {
        if (empty($apiKey)) {
            throw new ApiKeyIsNotSet();
        }
    }
}
