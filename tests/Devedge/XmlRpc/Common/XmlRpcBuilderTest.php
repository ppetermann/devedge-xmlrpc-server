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

}
