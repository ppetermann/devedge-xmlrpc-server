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

    public static function createResponse($data)
    {
        $response = new \SimpleXMLElement("<methodResponse></methodResponse>");
        $params = $response->addChild("params");
        $param = $params->addChild("param");
        $data = static::typeByGuess($data);
        $param->addChild($data->getName());
        $param->{$data->getName()} = $data;
        return $response->asXML();
    }

    /**
     * @param mixed $value
     * @throws \Exception
     * @return \SimpleXmlElement
     */
    public static function typeByGuess($value)
    {
        switch(true) {
            case is_int($value):
                return static::createInt($value);
                break;
            case is_string($value):
                return static::createString($value);
                break;
            case is_array($value) && static::isAssoc($value):
                return static::createStruct($value);
                break;
            case is_array($value) && !static::isAssoc($value):
                return static::createArray($value);
                break;
            case is_bool($value):
                return static::createBoolean($value);
                break;
            case is_float($value) || is_double($value) || is_real($value):
                return static::createDouble($value);
                break;
            case is_object($value) && $value instanceof \DateTime:
                return static::createDateTimeIso8601($value);
            case is_null($value):
                // @todo check if extension is enabled, we might want to behave differently
                // if it isn't
                return static::createNil();
                break;
            default:
                throw new \Exception(sprintf('don\'t know how to serialize: %s', gettype($value)));

        }
    }

    /**
     * @param array $input
     * @return \SimpleXMLElement
     */
    public static function createArray(array $input)
    {
        $array = simplexml_load_string("<array></array>");
        $data = $array->addChild("data");
        foreach($input as $value) {
            $valxml = $data->addChild("value");
            $value = static::typeByGuess($value);
            $valxml->addChild($value->getName());
            $valxml->{$value->getName()} =$value;
        }
        return $array;
    }

    /**
     * @param string $value
     * @return \SimpleXMLElement
     */
    public static function createBase64($value)
    {
        return simplexml_load_string("<base64>$value</base64>");
    }

    /**
     * @param boolean $value
     * @return \SimpleXMLElement
     */
    public static function createBoolean($value)
    {
        $value = (int) $value;
        return simplexml_load_string("<boolean>$value</boolean>");
    }


    public static function createDateTimeIso8601(\DateTime $dateTime)
    {
        $value = $dateTime->format(\DateTime::ISO8601);
        return simplexml_load_string("<dateTime.iso8601>$value</dateTime.iso8601>");

    }

    /**
     * @param double $value
     * @return \SimpleXMLElement
     */
    public static function createDouble($value)
    {
        return simplexml_load_string("<double>$value</double>");
    }

    /**
     * @param int $value
     * @return \SimpleXMLElement
     */
    public static function createInt($value)
    {
        return simplexml_load_string("<int>$value</int>");
    }

    /**
     * @param string $value
     * @return \SimpleXMLElement
     */
    public static function createString($value)
    {
        return simplexml_load_string("<string>$value</string>");
    }

    /**
     * @param array $input
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public static function createStruct(array $input)
    {
        $struct = simplexml_load_string("<struct></struct>");
        foreach($input as $key => $val)
        {
            $member = $struct->addChild("member");
            $member->addChild("name", $key);
            $member->addChild("value");
            $member->{"value"} = static::typeByGuess($val);
        }
        return $struct;
    }

    /**
     * this is not standard xml-rpc, but an extension
     * @see http://ontosys.com/xml-rpc/extensions.php
     */
    public static function createNil()
    {
        return simplexml_load_string("<nil />");
    }

    /**
     * simple check if array is associative, using the array keys
     * @param array $value
     * @return bool
     */
    protected static function isAssoc(array $value)
    {
        $array = array_keys($value);
        return ($array !== array_keys($array));
    }
}
