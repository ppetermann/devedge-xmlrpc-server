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

## Links
 * https://devedge.eu
 * http://xmlrpc.scripting.com/spec
