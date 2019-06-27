<?php


namespace V1\Exceptions;


use Exception;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;

class ApiKeyIsNotSet extends Exception
{
    public function __construct()
    {
        parent::__construct("The API key for authorization is not set",
            IlluminateJsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
