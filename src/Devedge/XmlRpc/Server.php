<?php
namespace Devedge\XmlRpc;

use Devedge\Log\NoLog;
use Devedge\XmlRpc\Common\XmlRpcParser;
use Devedge\XmlRpc\Server\XmlRpcBuilder;
use Devedge\XmlRpc\Server\HandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class Server
 */
class Server implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string Version string (to be updated by RMT, dont change manually)
     */
    public static $version = "0.1.0";

    /**
     * @var bool
     */
    public $exceptionOnError = false;

    /** @var HandlerInterface[]  */
    protected $handlers = [];

    public function registerHandler(HandlerInterface $handler)
    {
        $this->getLogger()->debug(
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
            $this->getLogger()->error(sprintf('could not parse request: %s', $request));
            return $this->handleError(new \Exception("could not parse request"));
        }

        // we catch all exceptions that can happen during handling, and use handle error on 'em
        try {
            list($namespace, $methodName) = explode(".", (string) $simpleXml->methodName);
            $this->getLogger()->info("handling call for $namespace, $methodName");
            $response = XmlRpcBuilder::createResponse(
                $this->handlers[$namespace]->handle(
                    $methodName,
                    XmlRpcParser::parseParams($simpleXml->params)
                )
            );
        } catch (\Exception $e) {
            $this->getLogger()->error(sprintf('an exception occured during execution: %s', $e->getMessage()));
            return $this->handleError($e);
        }
        return $response;
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
            return $this->exceptionToFaultResponse($e);
        }
    }

    /**
     * Convert an Exception to an XML-RPC fault response
     * @param \Exception $e
     * @return string
     */
    private function exceptionToFaultResponse(\Exception $e)
    {
        return XmlRpcBuilder::createFault($e->getCode(), $e->getMessage());
    }


    protected function getLogger()
    {
        // if no logger is set on the first getLogger call we set a null Logger, so we can proceed without
        // errors.
        if (is_null($this->logger)) {
            $this->logger = new NoLog();
        }
        return $this->logger;
    }
}
