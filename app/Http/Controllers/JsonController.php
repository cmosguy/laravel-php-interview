<?php


namespace App\Http\Controllers;


use App\Modules\Response\Json\JsonResponseFactory;

abstract class JsonController extends Controller
{
    /**
     * @var JsonResponseFactory
     */
    private $response;

    public function __construct(JsonResponseFactory $response)
    {
        $this->response = $response;
    }

    protected function response(): JsonResponseFactory
    {
        return $this->response;
    }
}
