<?php
namespace Devedge\XmlRpc\Server;

class XmlRpcBuilder extends \Devedge\XmlRpc\Common\XmlRpcBuilder
{
    /**
     * creates a xml-rpc fault
     *
     * @param int $code
     * @param string $message
     * @return string
     */
    public static function createFault($code, $message)
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

    /**
     * @param mixed $data
     * @return string
     * @throws \Exception
     */
    public static function createResponse($data)
    {
        $response = new \SimpleXMLElement("<methodResponse></methodResponse>");
        $params = $response->addChild("params");
        $param = $params->addChild("param");
        $value = $param->addChild("value");
        $dataElement = static::typeByGuess($data);
        $value->addChild($dataElement->getName());
        $value->{$dataElement->getName()} = $dataElement;

        return $response->asXML();
    }
}
