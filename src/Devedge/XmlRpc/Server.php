<?php
namespace Devedge\XmlRpc;

use Devedge\Log\NoLog\NoLog;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class Server
 */
class Server implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string Version string (to be updated by RMT, dont change manually)
     */
    public static $version = "0.0.0";

    /**
     * @var bool
     */
    public $exceptionOnError = false;

    protected $handlers = [];

    /**
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(LoggerInterface $loggerInterface = null)
    {
        // initialize Logger with a non-logging logger, so we don't have to check if it is set over and over again
        if (is_null($loggerInterface)) {
            $this->logger = new NoLog();
        } else {
            $this->logger = $loggerInterface;
        }

    }

    public function registerHandler(HandlerInterface $handler)
    {
        $this->logger->debug(
            sprintf('registering handler: %s for namespace: %s', get_class($handler), $handler->getNamespace())
        );

        $this->handlers[$handler->getNamespace()] = $handler;
    }

    /**
     * @param string $request
     * @throws \Exception
     * @return string
     */
    public function handle($request)
    {
        // surpressing warning, as we handle the error properly
        if (!($simpleXml = @simplexml_load_string($request))) {
            $this->logger->error(sprintf('could not parse request: %s', $request));
            return $this->handleError(new \Exception("could not parse request"));
        }

        return "";
    }

    /**
     * @param \Exception $e
     * @return string
     * @throws \Exception
     */
    public function handleError(\Exception $e)
    {
        if ($this->exceptionOnError) {
            throw $e;
        } else {
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Convert an Exception to an XML-RPC fault response
     * @param \Exception $e
     * @return string
     */
    private function exceptionToResponse(\Exception $e)
    {
        $response = new \SimpleXMLElement("<methodResponse></methodResponse>");
        $struct = $response->addChild("fault")->addChild("value")->addChild("struct");

        $member = $struct->addChild("member");
        $member->addChild("name", "faultCode");
        $member->addChild("value")->addChild("int", $e->getCode());

        $member = $struct->addChild("member");
        $member->addChild("name", "faultString");
        $member->addChild("value", $e->getMessage());

        return $response->asXML();
    }
}
