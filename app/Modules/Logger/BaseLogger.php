<?php


namespace App\Modules\Logger;


use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseLogger
{
    /**
     * Build the attributes to show in the log
     * @param Response $response
     * @param Request  $request
     * @return array
     */
    protected function buildAttributes(Response $response, Request $request): array
    {
        return [
            'client' => $request->getClientIp(),
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'requestHeaders' => $request->headers->all(),
            'requestBody' => $this->requestBody($request->all()),
            'responseHeaders' => $response->headers->all(),
            'responseBody' => $this->responseBody($response->getContent()),
            'code' => $response->getStatusCode(),
        ];
    }

    /**
     * Decode the json response
     * @param string $content
     * @return mixed
     */
    private function responseBody(string $content): array
    {
        return json_decode($content, true);
    }

    /**
     * Build the request body
     * @param $data
     * @return array
     */
    private function requestBody($data): array
    {
        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $value = json_encode($value, true);
            }
            $body[$key] = $value;
        }

        return $body ?? [];
    }
}
