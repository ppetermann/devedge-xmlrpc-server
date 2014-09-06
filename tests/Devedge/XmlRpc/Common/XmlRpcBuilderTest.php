<?php
namespace Devedge\XmlRpc\Server;

use Devedge\XmlRpc\Common\XmlRpcBuilder;
use Devedge\XmlRpc\Server;
use PHPUnit_Framework_TestCase;

class XmlRpcBuilderTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    public function testCreateFault()
    {
        $this->assertEquals("<?xml version=\"1.0\"?>\n<methodResponse><fault><value><struct><member><name>faultCode</name><value><int>0</int></value></member><member><name>faultString</name><value>teststring</value></member></struct></value></fault></methodResponse>\n", XmlRpcBuilder::createFault(0, "teststring"));
    }


    public function testCreateResponse()
    {
        $this->assertEquals("<?xml version=\"1.0\"?>\n<methodResponse><params><param><int>1</int></param></params></methodResponse>\n", XmlRpcBuilder::createResponse(1));
        $this->assertEquals("<?xml version=\"1.0\"?>\n<methodResponse><params><param><string>test</string></param></params></methodResponse>\n", XmlRpcBuilder::createResponse("test"));
        $dt = new \DateTime();
        $dt->setTimezone(new \DateTimeZone("UTC"));
        $dt->setTimestamp(332467200);
        $this->assertEquals("<?xml version=\"1.0\"?>\n<methodResponse><params><param><dateTime.iso8601>1980-07-15T00:00:00+0000</dateTime.iso8601></param></params></methodResponse>\n", XmlRpcBuilder::createResponse($dt));
    }

}
