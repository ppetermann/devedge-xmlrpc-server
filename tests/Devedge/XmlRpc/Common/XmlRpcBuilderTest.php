<?php
namespace Devedge\XmlRpc\Common;

use Devedge\XmlRpc\Server;
use PHPUnit_Framework_TestCase;

class XmlRpcBuilderTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    public function testCreateInt()
    {
        $this->assertEquals("<?xml version=\"1.0\"?>\n<int>1</int>\n", XmlRpcBuilder::createInt(1)->asXML());
    }

    public function testCreateString()
    {
        $this->assertEquals("<?xml version=\"1.0\"?>\n<string>1</string>\n", XmlRpcBuilder::createString("1")->asXML());
    }

    public function testDateTimeIso8601()
    {
        $dt = new \DateTime();
        $dt->setTimezone(new \DateTimeZone("UTC"));
        $dt->setTimestamp(332467200);
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<dateTime.iso8601>1980-07-15T00:00:00+0000</dateTime.iso8601>\n",
            XmlRpcBuilder::createDateTimeIso8601($dt)->asXML()
        );
    }

    public function testCreateBase64()
    {
        // this is a bit of a cheat as we don't put base64 stuff through here..
        $this->assertEquals("<?xml version=\"1.0\"?>\n<base64>1</base64>\n", XmlRpcBuilder::createBase64("1")->asXML());
    }

    public function testCreateDouble()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::createDouble((double)1.01)->asXML()
        );
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::createDouble((float)1.01)->asXML()
        );
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::createDouble((real)1.01)->asXML()
        );
    }

    public function testCreateArray()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<array><data><value><string>foo</string></value><value><int>1</int></value></data></array>\n",
            XmlRpcBuilder::createArray(["foo", 1])->asXML()
        );
    }

    public function testCreateStruct()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<struct><member><name>foo</name><value><string>bar</string></value></member><member><name>1</name><value><int>2</int></value></member></struct>\n",
            XmlRpcBuilder::createStruct(["foo" => "bar", 1 => 2])->asXML()
        );
    }

    public function testCreateBoolean()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<boolean>1</boolean>\n",
            XmlRpcBuilder::createBoolean(true)->asXML()
        );

    }

    public function testIsAssoc()
    {
        $this->assertFalse(XmlRpcBuilder::isAssoc(["one", "two", "three"]));
        $this->assertTrue(XmlRpcBuilder::isAssoc(["one" => "one", "two" => "two", "three" => "three"]));
        $this->assertTrue(XmlRpcBuilder::isAssoc([1 => "one", 3 => "three", 2 => "two"]));
    }

    public function testTypeByGuess()
    {
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<string>string</string>\n",
            XmlRpcBuilder::typeByGuess("string")->asXML()
        );
        $this->assertEquals("<?xml version=\"1.0\"?>\n<int>1</int>\n", XmlRpcBuilder::typeByGuess(1)->asXML());
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<array><data><value><int>1</int></value></data></array>\n",
            XmlRpcBuilder::typeByGuess([1])->asXML()
        );
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<struct><member><name>foo</name><value><string>bar</string></value></member></struct>\n",
            XmlRpcBuilder::typeByGuess(["foo" => "bar"])->asXML()
        );

        $dt = new \DateTime();
        $dt->setTimezone(new \DateTimeZone("UTC"));
        $dt->setTimestamp(332467200);
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<dateTime.iso8601>1980-07-15T00:00:00+0000</dateTime.iso8601>\n",
            XmlRpcBuilder::typeByGuess($dt)->asXML()
        );

        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::typeByGuess((double)1.01)->asXML()
        );
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::typeByGuess((float)1.01)->asXML()
        );
        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<double>1.01</double>\n",
            XmlRpcBuilder::typeByGuess((real)1.01)->asXML()
        );

        $this->assertEquals(
            "<?xml version=\"1.0\"?>\n<boolean>1</boolean>\n",
            XmlRpcBuilder::typeByGuess(true)->asXML()
        );

    }

}
