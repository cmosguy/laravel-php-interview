<?php

namespace App\Exceptions;

use App\Modules\Response\Json\JsonResponseFactory;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * @var JsonResponseFactory
     */
    private $json;

    public function __construct(Container $container, JsonResponseFactory $json)
    {
        parent::__construct($container);
        $this->json = $json;
    }

    /**
     * A list of the exception types that are not reported.
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     * @param  Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response|IlluminateJsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            return $this->jsonResponse($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Return json representation of the exception
     * @param Exception $exception
     * @return IlluminateJsonResponse
     */
    private function jsonResponse(Exception $exception): IlluminateJsonResponse
    {
        if ($exception instanceof ValidationException) {
            return $this->json->unprocessableEntity([
                'validator' => $exception->validator->getMessageBag()
            ], $exception->getMessage());
        } elseif ($exception instanceof AuthenticationException) {
            return $this->json->unauthorized($exception->getMessage());
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->json->notFound('Route does not exist.');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->json->methodNotAllowed('Method not allowed on this route.');
        }

        return $this->json->internalError($exception->getMessage());
    }
}
