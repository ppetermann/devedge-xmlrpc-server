<?php
namespace Devedge\XmlRpc\Server;

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

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage could not parse request
     */
    public function testHandleInvalidXmlNoException()
    {
        $server = new Server();
        $server->exceptionOnError = false;
        $this->assertEquals("", $server->handle("no valid xml here"));
    }
}
