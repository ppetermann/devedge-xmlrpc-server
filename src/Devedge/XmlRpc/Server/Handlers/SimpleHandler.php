<?php

namespace Devedge\XmlRpc\Server\Handlers;

use Devedge\XmlRpc\Server\HandlerInterface;

/***
 * Class SimpleHandler
 * the most simple of handlers, will take a random object and call its methods
 * @package Devedge\XmlRpc\Server\Handlers
 */
class SimpleHandler implements HandlerInterface
{

    protected $myObject;

    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @var string
     */
    protected $namespace;

    public function __construct($object, $namespace = null)
    {
        $this->myObject = $object;
        $this->reflectionClass = new \ReflectionClass($this->myObject);
        if (is_null($namespace)) {
            $namespace = __CLASS__;
        }
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        $methods = [];
        foreach($this->reflectionClass->getMethods() as $method)
        {
            if (
                $method->isPublic() // only public
                && !$method->isStatic() // no static
                && substr($method->getName(), 0, 2) != '__' // no magic
            ){
                $methods[] = $method->getName();
            }

        }
        return $methods;
    }

    /**
     * @param string $method
     * @param array $params
     * @return string
     */
    public function handle($method, $params)
    {
        $method = $this->reflectionClass->getMethod($method);
        return $method->invokeArgs($this->myObject, $params);
    }

    /**
     * @return String identifying the xml-rpc namespace to be used for this handler (1 handler per namespace)
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
