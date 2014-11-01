<?php
namespace Devedge\XmlRpc;

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

    public function testHandleRequest()
    {
        $server = new Server();
        $testobject = new ServerTestObject();
        $handler = new Server\Handlers\SimpleHandler($testobject);
        $server->registerHandler($handler);

        // @todo this should be moved to a handler test
        $this->assertEquals(["runTest1"], $handler->getMethods());

        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<methodResponse><params><param><value><boolean>1</boolean></value></param></params></methodResponse>\n",
            $server->handle(
                "<?xml version=\"1.0\"?><methodCall><methodName>Devedge\\XmlRpc\\Server\\Handlers\\SimpleHandler.runTest1</methodName><params><param><value><i4>42</i4></value></param></params></methodCall>"
            )
        );
        $this->assertEquals(42, $testobject->testValue);
    }



    public function testHandleRequestOfNonExistingMethod()
    {
        $server = new Server();
        $testobject = new ServerTestObject();
        $handler = new Server\Handlers\SimpleHandler($testobject);
        $server->registerHandler($handler);

        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<methodResponse><fault><value><struct><member><name>faultCode</name><value><int>0</int></value></member><member><name>faultString</name><value>Method runTest2 does not exist</value></member></struct></value></fault></methodResponse>\n",
            $server->handle(
                "<?xml version=\"1.0\"?><methodCall><methodName>Devedge\\XmlRpc\\Server\\Handlers\\SimpleHandler.runTest2</methodName><params><param><value><i4>42</i4></value></param></params></methodCall>"
            )
        );
    }


}

class ServerTestObject
{
    public $testValue;

    public function runTest1($value)
    {
        $this->testValue = $value;
        return true;
    }

    // we don't want to see this method
    protected function invisibleTest()
    {

    }
}

