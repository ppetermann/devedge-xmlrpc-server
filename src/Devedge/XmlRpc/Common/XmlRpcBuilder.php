<?php
namespace Devedge\XmlRpc\Common;

class XmlRpcBuilder
{
    /**
     * creates a xml-rpc fault
     * @param int $code
     * @param string $message
     * @return string
     */
    static public function createFault($code, $message)
    {
        $response = new \SimpleXMLElement("<methodResponse></methodResponse>");
        $struct = $response->addChild("fault")->addChild("value")->addChild("struct");

        $member = $struct->addChild("member");
        $member->addChild("name", "faultCode");
        $member->addChild("value")->addChild("int", $code);

        $member = $struct->addChild("member");
        $member->addChild("name", "faultString");
        $member->addChild("value", $message);

        return $response->asXML();
    }
}