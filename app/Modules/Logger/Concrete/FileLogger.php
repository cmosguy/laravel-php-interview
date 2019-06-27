<?php


namespace App\Modules\Logger\Concrete;


use App\Modules\Logger\BaseLogger;
use App\Modules\Logger\Contracts\ClientLogger;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class FileLogger extends BaseLogger implements ClientLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Write a incoming request and the response that will be returned to client
     * @param Request  $request
     * @param Response $response
     */
    public function writeIncoming(Request $request, Response $response): void
    {
        $this->logger->debug(json_encode($this->buildAttributes($response, $request)));
    }
}
