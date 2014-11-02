# devedge/xmlrpc-server
[![Latest Stable Version](https://poser.pugx.org/devedge/xmlrpc-server/v/stable.png)](https://packagist.org/packages/devedge/xmlrpc-server)
[![License](https://poser.pugx.org/devedge/xmlrpc-server/license.png)](https://packagist.org/packages/devedge/xmlrpc-server)
[![Total Downloads](https://poser.pugx.org/devedge/xmlrpc-server/downloads.png)](https://packagist.org/packages/devedge/xmlrpc-server)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ppetermann/devedge-xmlrpc-server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ppetermann/devedge-xmlrpc-server/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ppetermann/devedge-xmlrpc-server/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ppetermann/devedge-xmlrpc-server/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b12e3c4f-8bcf-402f-a74e-0f0e76626fc1/mini.png)](https://insight.sensiolabs.com/projects/b12e3c4f-8bcf-402f-a74e-0f0e76626fc1)

A library to provide services through xml-rpc

## License
MIT Style, see LICENSE.txt

## Notes
currently this requires the current Psr DRAFT for a caching standard, this is supposed to be replaced by using
the interface provided once php-fig has released the real standard. Until then this package will depend on devedge/stubs,
which includes a copy of the psr interface as well as a null implementation of that and psr/log.

## Usage
Right now this library is pretty limited in what it can do - which also means that usage is extremly easy.
This package will give you the tools that you need to integrate and xmlrpc service into your application. 

an simple example script, that could be run by any webserver would be:
```php

    <?php
    // use autoloading for the composer stuff
    require_once "../vendor/autoload.php";

    
    // create the server instance
    $server = new Devedge\XmlRpc\Server();

    
    // create an instance of your service
    // a service instance should have the methods that can be called through the xmlrpc interface.  
    $service = new Example\MyService();
    
    // create an instance of Devedge\XmlRpc\Server\Handlers\SimpleHandler. This class implements the
    // handler interface and allows to register any regular object to be used for handling the requests
    // simple handler will expose all public methods that don't start with __ towards the xmlrpc interface
    // make sure you only have methods that you actually want exposed!
    // the second parameter is a "namespace", expressing that all xmlrpc calls starting with "example." 
    // will be handled by this handler. 
    $handler = new Devedge\XmlRpc\Server\Handlers\SimpleHandler($service, "example")
    
    // as Devedge\XmlRpc\Server\Handlers\SimpleHandler implements the handler interface, the handler instance
    // can be registered as a handler with the server.
    // the server allows to register more than one handler (as long as they use different "namespaces"), and 
    // if a handler carries the namespace "default" it will also be used for methods that are called through xmlrpc
    // without a namespace set
    $server->registerHandler($handler);
    
    // lets be nice and set an xml content header.
    header("Content-Type: text/xml");
    
    // the handle method takes xmlrpc methodCall xml, and executes the handling
    // whatever return value the method of service is will be serialized as an 
    // xmlrpc answer, and available as return value of handle.
    echo $server->handle(file_get_contents('php://input'));

```

Other stuff you need to know about Devedge\XmlRpc\Server:

 * it has a method called setLogger() which takes an psr/log compatible logger as an argument, and then will cause theserver to log various things to that logger.
 * it has a boolean member named exceptionOnError (which defaults to false). If this is set to true Exceptions thrown in the handler / service will not be converted to xmlrpc faults. In that case the handle() method will rethrow the Exception originally thrown 

Other stuff you need to know about Devedge\XmlRpc\Server\Handlers\SimpleHandler:

 * SimpleHandler is exactly what the name sais, it is an extremly simple handler. It will for example not check the signature of the method called. If a request for a specific method is send, it will try to call this method with all arguments that it got.  

You can easy extend functionality (signature check, caching etc.) by writing your own handlers (as long as you implement HandlerInterface). 
 

## Links

 * https://devedge.eu
 * http://xmlrpc.scripting.com/spec
 
