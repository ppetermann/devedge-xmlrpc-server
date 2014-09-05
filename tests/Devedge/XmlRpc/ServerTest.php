<?php
namespace Devedge\XmlRpc\Server;

use Devedge\XmlRpc\Server;
use PHPUnit_Framework_TestCase;

class Servertest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage could not parse request
     */
    public function testHandleInvalidXml()
    {
        $server = new Server();
        $server->exceptionOnError = true;
        $server->handle("no valid xml here");
    }

    public function testHandleInvalidXmlNoException()
    {
        $server = new Server();
        $server->exceptionOnError = false;
        $this->assertEquals("<?xml version=\"1.0\"?>\n<methodResponse><fault><value><struct><member><name>faultCode</name><value><int>0</int></value></member><member><name>faultString</name><value>could not parse request</value></member></struct></value></fault></methodResponse>\n", $server->handle("no valid xml here"));
    }
}
