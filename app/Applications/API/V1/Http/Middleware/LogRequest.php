<?php


namespace V1\Http\Middleware;


use App\Modules\Logger\Contracts\ClientLogger;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogRequest
{
    /**
     * @var ClientLogger
     */
    private $logger;

    public function __construct(ClientLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log each request and response
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $response = $next($request);

        $this->logger->writeIncoming($request, $response);

        return $response;
    }
}
