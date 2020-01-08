# LosLog
[![Build Status](https://travis-ci.org/Lansoweb/LosLog.svg?branch=master)](https://travis-ci.org/Lansoweb/LosLog) [![Latest Stable Version](https://poser.pugx.org/los/loslog/v/stable.svg)](https://packagist.org/packages/los/loslog) [![Total Downloads](https://poser.pugx.org/los/loslog/downloads.svg)](https://packagist.org/packages/los/loslog) [![Coverage Status](https://coveralls.io/repos/Lansoweb/LosLog/badge.svg)](https://coveralls.io/r/Lansoweb/LosLog) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Lansoweb/LosLog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Lansoweb/LosLog/?branch=master) [![SensioLabs Insight](https://img.shields.io/sensiolabs/i/7a5421e3-5494-4ab8-bbfc-9fbae368148d.svg?style=flat)](https://insight.sensiolabs.com/projects/7a5421e3-5494-4ab8-bbfc-9fbae368148d) [![Dependency Status](https://www.versioneye.com/user/projects/54da834fc1bbbda013000282/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54da834fc1bbbda013000282)

## Introduction
This is the 2.0 documentation version. Please refer to the README-1.0 for 1.0 documentation.

This module provides some useful log classes:

- LosLog = An error middleware for PSR-7 compatible frameworks/applications
- HttpLog = Use to log request and response from a PSR-7 application
- ErrorLogger = PHP error
- ExceptionLogger = PHP Exception
- StaticLogger = "Shortcut" to a generic file logger. Can be attached to the Z-Ray in Zend Server
- Rollbar writer = A Rollbar writer. Uploads errors and exceptions to [Rollbar](https://rollbar.com) service

## Requirements

* php >= 5.6.0
* laminas/laminas-stratigility
* laminas/laminas-diactoros
* laminas/laminas-log

## Instalation

For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

```bash
php composer.phar require los/loslog
```

## Usage
Copy the file loslog.global.php.dist to your config/autoload/ , rename it to
loslog.global.php and change the default options, if needed.

### LosLog Middleware

#### Zend Expressive
Expressive 2.0 introduced a new method to handle errors, using listeners to the ErrorHandler and delegator factories, 
so this is the preferable method.

Add the delegator factory to the ErrorHandler, like:
```php
return [
    'dependencies' => [
        'factories' => [
            LosMiddleware\LosLog\LosLog::class => LosMiddleware\LosLog\LosLogFactory::class,
        ],
        'delegators' => [
      		ErrorHandler;::class => [
            	LosMiddleware\LosLog\ErrorHandlerListenerDelegatorFactory::class,
        	],
    	],
    ],
];
```

#### General use
If using other framework, you can add the LosLogFactory to your factory system, manually create a LosLog instance or
call the LosLogFactory directly.

### HttpLog Middleware

It will log requests and responses in compact or full mode. It will include X-Request-Id and X-Response-Time headers if present.

#### Zend Expressive
Add the middleware as the first middleware in your pipeline, like:
```php
return [
    'middleware_pipeline' => [
        'before' => [
            'middleware' => [
                LosMiddleware\LosLog\HttpLog::class,
            ],
            'priority' => 10000,
        ],
    ],
];
```

Set the desired options in loslog.global.php (or loslog.local.php):
```php
'http_logger_file' => 'http.log',
'log_request' => true,
'log_response' => true,
'full' => false,
```

You can integrate with [los/request-id](https://github.com/Lansoweb/request-id) and [los/response-time](https://github.com/Lansoweb/response-time).
The order is important, use as bellow:

```php
return [
    'middleware_pipeline' => [
        'before' => [
            'middleware' => [
                LosMiddleware\RequestId\RequestId::class,
                LosMiddleware\LosLog\HttpLog::class,
                LosMiddleware\ResponseTime\ResponseTime::class
            ],
            'priority' => 10000,
        ],
    ],
];
```

This will produce:
```
2015-11-20T14:04:51+00:00 INFO (6): Request: GET /shop/v1/item/1 RequestId: 12dbf2d2-52c5-4954-b573-3aa2fee58612
2015-11-20T14:04:51+00:00 INFO (6): Response: 200 OK RequestId: 12dbf2d2-52c5-4954-b573-3aa2fee58612 ResponseTime: 14.90ms
```

### ErrorLogger
To enable the ErrorLogger just add the registerHandlers inside your public/index.php

#### Zend Framework 2
```php
chdir(dirname(__DIR__));

require 'init_autoloader.php';

\LosMiddleware\LosLog\ErrorLogger::registerHandlers();

Laminas\Mvc\Application::init(require 'config/application.config.php')->run();
```
#### Zend Expressive
```php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\LosMiddleware\LosLog\ErrorLogger::registerHandlers('error.log', '/tmp');

/* @var \Mezzio\Application $api */
$app = $container->get('Mezzio\Application');

$app->run();
```


You can use the logger with your phpunit tests. Just call it in your bootstrap file just after the autoload is created:
```php
\LosLog\Log\ErrorLogger::registerHandlers();
```

#### Output example
```
2015-10-30T17:58:10-02:00 ERR (3): Error: Call to a member function format() on a non-object in <filename> on line <line>
```

The default logfile is data/log/error.log

### ExceptionLogger
To enable the ExceptionLogger just add the registerHandlers inside your public/index.php

#### Zend Framework 2
```php
chdir(dirname(__DIR__));

require 'init_autoloader.php';

\LosMiddleware\LosLog\ExceptionLogger::registerHandlers('exception.log', '/tmp');

Laminas\Mvc\Application::init(require 'config/application.config.php')->run();
```
#### Zend Expressive
```php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\LosMiddleware\LosLog\ExceptionLogger::registerHandlers('exception.log', '/tmp');

/* @var \Mezzio\Application $api */
$app = $container->get('Mezzio\Application');

$app->run();
```

#### Output example

```
2015-11-01T09:23:53-02:00 ERR (3): Exception- An exception was raised while creating "Application\Service\Test"; no instance returned in <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php in line 733.
Previous: "data/logs2/erros.log" cannot be opened with mode "a" in <dir>/vendor/zendframework/zendframework/library/Zend/Log/Writer/Stream.php in line 87.
Previous: fopen(data/logs2/erros.log): failed to open stream: No such file or directory in <dir>/vendor/zendframework/zendframework/library/Zend/Log/Writer/Stream.php in line 84.
Trace:
#0 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(843): Laminas\ServiceManager\ServiceManager->createServiceViaCallback(Object(Closure), 'teste', 'Application\Service\Tes...')
#1 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(487): Laminas\ServiceManager\ServiceManager->createFromFactory('teste', 'Application\Service\Tes...')
#2 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(442): Laminas\ServiceManager\ServiceManager->create(Array)
#3 <dir>/src/Application/Module.php(29): Laminas\ServiceManager\ServiceManager->get('Application\Service\Tes...')
#4 [internal function]: Application\Module->onBootstrap(Object(Laminas\Mvc\MvcEvent))
#5 <dir>/vendor/zendframework/zendframework/library/Zend/EventManager/EventManager.php(468): call_user_func(Array, Object(Laminas\Mvc\MvcEvent))
#6 <dir>/vendor/zendframework/zendframework/library/Zend/EventManager/EventManager.php(208): Laminas\EventManager\EventManager->triggerListeners('bootstrap', Object(Laminas\Mvc\MvcEvent), Array)
#7 <dir>/vendor/zendframework/zendframework/library/Zend/Mvc/Application.php(146): Laminas\EventManager\EventManager->trigger('bootstrap', Object(Laminas\Mvc\MvcEvent))
#8 <dir>/vendor/zendframework/zendframework/library/Zend/Mvc/Application.php(243): Laminas\Mvc\Application->bootstrap()
#9 <dir>/public/index.php(23): Laminas\Mvc\Application::init(Array)
#10 {main}
```

The default logfile is data/log/exception.log

### StaticLogger
This logger is usually used to log development or debug messages, arrays and objects. Just call it statically anywhere in your code.

```php
\LosMiddleware\LosLog\StaticLogger::save("Test message");
\LosMiddleware\LosLog\StaticLogger::save("Test message 2", 'test.log');
```
will generate
```
2015-10-29T19:32:30-02:00 DEBUG (6): Test message
```

Or an object:
```php
\LosMiddleware\LosLog\StaticLogger::save($myObj);
```
will generate
```
2015-10-30T17:26:37-03:00 DEBUG (7): {"User\\Entity\\User":[],"nome":{"type":"string","content":"Leandro"},"sobrenome":{"type":"string","content":"Silva"},"permissao":{"type":"string","content":"usuario"},"email":{"type":"string","content":"leandro@leandrosilva.info"},"acessos":{"type":"object","class":"Doctrine\\ORM\\PersistentCollection"},"login":{"type":"NULL","content":null},"senha":{"type":"string","content":"admin"},"inputFilter":{"type":"NULL","content":null},"id":{"type":"integer","content":3},"cadastrado":{"type":"object","class":"DateTime"},"atualizado":{"type":"object","class":"DateTime"}}
```

The default logfile is data/log/static.log

#### Z-Ray
Z-Ray is an awesome resource from Zend Server that provides several information about the request, errors and the framework. It also has the possibility to add your own informations, so i added the StaticLogger messages to it.

More information can be seen [here](http://www.zend.com/en/products/server/z-ray-top-7-features).

##### Installation
The LosLog module is available via the Official Z-Ray plugin system, just access the tab from your Zend Server UI and install it.

##### Usage
Just use the StaticLogger and the messages will appear inside a LosLog section of the Z-Ray bar.

Optionally, you can pass a "null" value to the file argument to use just the Z-Ray, without writing the message to a file:

```php
\LosMiddleware\LosLog\StaticLogger::save("Test message", null);
```
