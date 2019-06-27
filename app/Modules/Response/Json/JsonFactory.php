<?php


namespace App\Modules\Response\Json;


use Illuminate\Http\JsonResponse as IlluminateJsonResponse;

class JsonFactory implements JsonResponseFactory
{
    public function ok(array $append = [], string $message = null): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Successfully executed.', $append);

        return $this->instance($data, IlluminateJsonResponse::HTTP_OK);
    }

    public function created(array $append = [], string $message = null): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Successfully created.', $append);

        return $this->instance($data, IlluminateJsonResponse::HTTP_CREATED);
    }

    public function forbidden(?string $message): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Forbidden.');

        return $this->instance($data, IlluminateJsonResponse::HTTP_FORBIDDEN);
    }

    public function unprocessableEntity(array $append = [], string $message = null): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Unprocessable entity.', $append);

        return $this->instance($data, IlluminateJsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function unauthorized(?string $message): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Unauthorized.');

        return $this->instance($data, IlluminateJsonResponse::HTTP_UNAUTHORIZED);
    }

    public function notFound(?string $message): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Entity not found.');

        return $this->instance($data, IlluminateJsonResponse::HTTP_NOT_FOUND);
    }

    public function methodNotAllowed(?string $message): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Method not allowed on this endpoint.');

        return $this->instance($data, IlluminateJsonResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    public function internalError(?string $message): IlluminateJsonResponse
    {
        $data = $this->buildResponseData($message ?? 'Internal error.');

        return $this->instance($data, IlluminateJsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Create the response with the message and any additional response data
     * @param string $message
     * @param array  $append
     * @return array
     */
    private function buildResponseData(string $message, array $append = []): array
    {
        return array_merge(['message' => $message], $append);
    }

    /**
     * Return the json response instance
     * @param array $data
     * @param int   $status
     * @return IlluminateJsonResponse
     */
    private function instance(array $data, int $status): IlluminateJsonResponse
    {
        return new IlluminateJsonResponse($data, $status);
    }
}
