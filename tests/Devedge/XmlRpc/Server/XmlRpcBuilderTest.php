<?php
namespace Devedge\XmlRpc\Server;

use Devedge\XmlRpc\Server;
use PHPUnit_Framework_TestCase;

class XmlRpcBuilderTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    public function testCreateFault()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<methodResponse><fault><value><struct><member><name>faultCode</name><value><int>0</int></value></member><member><name>faultString</name><value>teststring</value></member></struct></value></fault></methodResponse>\n",
            XmlRpcBuilder::createFault(0, "teststring")
        );
    }

    public function testCreateResponse()
    {
        // we test with int, as if one works others work too.
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<methodResponse><params><param><value><int>1</int></value></param></params></methodResponse>\n",
            XmlRpcBuilder::createResponse(1)
        );
    }
}
