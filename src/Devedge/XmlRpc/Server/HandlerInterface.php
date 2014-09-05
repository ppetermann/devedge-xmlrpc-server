<?php
namespace Devedge\XmlRpc\Server;

interface HandlerInterface
{
    /**
     * @return array
     */
    public function getMethods();

    /**
     * @param string $request
     * @return string
     */
    public function handle($request);

    /**
     * @return String identifying the xml-rpc namespace to be used for this handler (1 handler per namespace)
     */
    public function getNamespace();
}