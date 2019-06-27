<?php


namespace V1\Exceptions;


use Exception;

class ModelWasNotDeleted extends Exception
{
    public function __construct(string $uuid, string $model, string $additional = '')
    {
        parent::__construct("Failed to delete [{$model}] with guid [{$uuid}]. {$additional}", 500);
    }
}
