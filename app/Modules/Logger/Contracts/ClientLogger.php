<?php


namespace App\Modules\Logger\Contracts;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface ClientLogger
{
    /**
     * Write a incoming request and the response that will be returned to client
     * @param Request  $request
     * @param Response $response
     */
    public function writeIncoming(Request $request, Response $response): void;
}
