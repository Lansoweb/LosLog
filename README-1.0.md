# LosLog
[![Build Status](https://travis-ci.org/Lansoweb/LosLog.svg?branch=master)](https://travis-ci.org/Lansoweb/LosLog) [![Latest Stable Version](https://poser.pugx.org/los/loslog/v/stable.svg)](https://packagist.org/packages/los/loslog) [![Total Downloads](https://poser.pugx.org/los/loslog/downloads.svg)](https://packagist.org/packages/los/loslog) [![Coverage Status](https://coveralls.io/repos/Lansoweb/LosLog/badge.svg)](https://coveralls.io/r/Lansoweb/LosLog) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Lansoweb/LosLog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Lansoweb/LosLog/?branch=master) [![SensioLabs Insight](https://img.shields.io/sensiolabs/i/7a5421e3-5494-4ab8-bbfc-9fbae368148d.svg?style=flat)](https://insight.sensiolabs.com/projects/7a5421e3-5494-4ab8-bbfc-9fbae368148d) [![Dependency Status](https://www.versioneye.com/user/projects/54da834fc1bbbda013000282/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54da834fc1bbbda013000282)

## Introduction
This module provides some useful log classes:

- ErrorLogger   = PHP error and Exception
- EntityLogger  = Doctrine ORM Entity
- SqlLogger     = Doctrine DBAL SQL
- StaticLogger  = "Shortcut" to a generic file logger. Can be attached to the Z-Ray in Zend Server
- RollbarLogger = Rollbar logger. Uploads errors and exceptions to [Rollbar](https://rollbar.com) service (NEW) 

## Requirements
This module only requires zendframework 2 [framework.zend.com](http://framework.zend.com/).

## Instalation
Instalation can be done with composer ou manually

### Installation with composer
For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

  1. Enter your project directory
  2. Create or edit your `composer.json` file with following contents:

     ```json
     {
         "minimum-stability": "dev",
         "require": {
             "los/loslog": "1.*"
         }
     }
     ```
  3. Run `php composer.phar install`
  4. Open `my/project/directory/config/application.config.php` and add `LosLog` to your `modules`
     
     Usually insert as the first module to enable catch errors and exceptions in all modules.
  
    ```php
    <?php
    return array(
        'modules' => array(
            'LosLog',
            'Application'
        ),
        'module_listener_options' => array(
            'config_glob_paths'    => array(
                'config/autoload/{,*.}{global,local}.php',
            ),
            'module_paths' => array(
                './module',
                './vendor',
            ),
        ),
    );
    ```

### Installation without composer

  1. Clone this module [LosLog](http://github.com/LansoWeb/LosLog) to your vendor directory
  2. Enable it in your config/application.config.php like the step 4 in the previous section.

## Usage
To change the options, copy the file loslog.global.php.dist to your config/autoload/ , rename it to 
loslog.global.php and change the default options.

### ErrorLogger
To enable the ErrorLogger just add the registerHandlers inside your public/index.php
 
```php
chdir(dirname(__DIR__));

require 'init_autoloader.php';

LosLog\Log\ErrorLogger::registerHandlers();

Zend\Mvc\Application::init(require 'config/application.config.php')->run();
```

You can use the logger with your phpunit tests. Just call it in your bootstrap file just after the autoload is created:
```php
LosLog\Log\ErrorLogger::registerHandlers();
```

#### Output examples

##### PHP Error
```
2012-10-30T17:58:10-02:00 ERR (3): Error: Call to a member function format() on a non-object in <filename> on line <line>
```

##### Exception
```
2012-11-01T09:23:53-02:00 ERR (3): Exception- An exception was raised while creating "Application\Service\Test"; no instance returned in <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php in line 733.
Previous: "data/logs2/erros.log" cannot be opened with mode "a" in <dir>/vendor/zendframework/zendframework/library/Zend/Log/Writer/Stream.php in line 87.
Previous: fopen(data/logs2/erros.log): failed to open stream: No such file or directory in <dir>/vendor/zendframework/zendframework/library/Zend/Log/Writer/Stream.php in line 84.
Trace:
#0 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(843): Zend\ServiceManager\ServiceManager->createServiceViaCallback(Object(Closure), 'teste', 'Application\Service\Tes...')
#1 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(487): Zend\ServiceManager\ServiceManager->createFromFactory('teste', 'Application\Service\Tes...')
#2 <dir>/vendor/zendframework/zendframework/library/Zend/ServiceManager/ServiceManager.php(442): Zend\ServiceManager\ServiceManager->create(Array)
#3 <dir>/src/Application/Module.php(29): Zend\ServiceManager\ServiceManager->get('Application\Service\Tes...')
#4 [internal function]: Application\Module->onBootstrap(Object(Zend\Mvc\MvcEvent))
#5 <dir>/vendor/zendframework/zendframework/library/Zend/EventManager/EventManager.php(468): call_user_func(Array, Object(Zend\Mvc\MvcEvent))
#6 <dir>/vendor/zendframework/zendframework/library/Zend/EventManager/EventManager.php(208): Zend\EventManager\EventManager->triggerListeners('bootstrap', Object(Zend\Mvc\MvcEvent), Array)
#7 <dir>/vendor/zendframework/zendframework/library/Zend/Mvc/Application.php(146): Zend\EventManager\EventManager->trigger('bootstrap', Object(Zend\Mvc\MvcEvent))
#8 <dir>/vendor/zendframework/zendframework/library/Zend/Mvc/Application.php(243): Zend\Mvc\Application->bootstrap()
#9 <dir>/public/index.php(23): Zend\Mvc\Application::init(Array)
#10 {main}
```
 
The default logfile is data/log/error.log

### EntityLogger
The first usage is to dump a Doctrine Entity to the screen, either HTML or console.

To do that just call a static function:
```php
echo \LosLog\Log\EntityLogger::dump($entity);
```

and it will print:
```
stdClass Object
(
    [__CLASS__] => User\Entity\User
    [name] => Admin
    [email] => admin@foo.com
    [password] => $2y$14$4n/JYSM7ZtSaZpg1/PgFZefzoblrmaMmMZga.nhf7TZNAd
    [id] => 1
    [created] => DateTime
)
```

The "dump" function provides two more arguments:
```php
public static function dump($entity, $maxDepth = 1, $toHtml = true)
```

The second argument indicates how depth will be the dump (other classes as properties, including collections) with default to 1 
and the third if the output will be HTML friendly (encloses the output in a "pre" tag) or false for a console version with a default to true.

```php
echo \LosLog\Log\EntityLogger::dump($entity, 2);
```

and it will print:
```
stdClass Object
(
    [__CLASS__] => User\Entity\User
    [name] => Admin
    [email] => admin@foo.com
    [password] => $2y$14$4n/JYSM7ZtSaZpg1/PgFZefzoblrmaMmMZga.nhf7TZNAd
    [id] => 1
    [access] => Array
        (
            [0] => User\Entity\Access
            [1] => User\Entity\Access
            [2] => User\Entity\Access
        )
    [created] => stdClass Object
        (
            [__CLASS__] => DateTime
            [date] => 2014-09-28T07:06:29-03:00
            [timezone] => America/Sao_Paulo
        )
)
```

```php
echo \LosLog\Log\EntityLogger::dump($entity, 3);
```

and it will print:
```
stdClass Object
(
    [__CLASS__] => User\Entity\User
    [name] => Admin
    [email] => admin@foo.com
    [password] => $2y$14$4n/JYSM7ZtSaZpg1/PgFZefzoblrmaMmMZga.nhf7TZNAd
    [id] => 1
    [access] => Array
        (
            [0] => stdClass Object
                (
                    [__CLASS__] => User\Entity\Access
                    [ip] => 10.1.1.2
                    [agent] => Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20100101 Firefox/32.0
                    [id] => 1
                    [created] => DateTime
                    [updated] => DateTime
                )

            [1] => stdClass Object
                (
                    [__CLASS__] => User\Entity\Access
                    [ip] => 10.1.1.3
                    [agent] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/6.1.3 Safari/537.75.14
                    [id] => 2
                    [created] => DateTime
                    [updated] => DateTime
                )
        )
    [created] => stdClass Object
        (
            [__CLASS__] => DateTime
            [date] => 2014-09-28T07:06:29-03:00
            [timezone] => America/Sao_Paulo
        )
)
```

The second usage of this class is to save database operations generated by your entities.

ATTENTION: This logger depends on [DoctrineORMModule](http://github.com/doctrine/DoctrineORMModule). 
Since its usage is optional, i did not put this requirement inside the composer.json

To enable this logger, register inside your doctrine's config (e.g. config/autoload/global.php)
```php
namespace App;
return array(
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'LosLog\Log\EntityLogger'
                )
            )
        )
    )
);
```

will generate: 
```
2012-10-29T19:30:46-02:00 DEBUG (7): Inserting entity Application\Entity\Client. Fields: {"nome":[null,"ClientName"],"created_on":[null,{"date":"2012-10-29 19:30:46","timezone_type":3,"timezone":"America\/Sao_Paulo"}]}
2012-10-29T19:32:23-02:00 DEBUG (7): Updating entity Application\Entity\Client with id 3. Fields: {"nome":["ClientName","ClientName2"]}
2012-10-29T19:36:53-02:00 DEBUG (7): Deleting entity Application\Entity\Client with id 3.
```

The default logfile is data/log/entity.log

### SqlLogger
With this logger you can save all Doctrine database operations within your application.

ATTENTION: This logger depends on [DoctrineModule](http://github.com/doctrine/DoctrineModule). 
Since its usage is optional, i did not put this requirement inside the composer.json

Edit the config/autoload/loslog.global.php file to enable this logger.

The default logfile is data/log/sql.log

### StaticLogger
This logger is usually used to log development or debug messages, arrays and objects. Just call it statically anywhere in your code.

```php
LosLog\Log\StaticLogger::save("Test message");
LosLog\Log\StaticLogger::save("Test message 2", 'test.log');
```
will generate
```
2012-10-29T19:32:30-02:00 DEBUG (6): Test message
```

Or an object:
```php
LosLog\Log\StaticLogger::save($myObj);
```
will generate
```
2013-07-30T17:26:37-03:00 DEBUG (7): {"User\\Entity\\User":[],"nome":{"type":"string","content":"Leandro"},"sobrenome":{"type":"string","content":"Silva"},"permissao":{"type":"string","content":"usuario"},"email":{"type":"string","content":"leandro@leandrosilva.info"},"acessos":{"type":"object","class":"Doctrine\\ORM\\PersistentCollection"},"login":{"type":"NULL","content":null},"senha":{"type":"string","content":"admin"},"inputFilter":{"type":"NULL","content":null},"id":{"type":"integer","content":3},"cadastrado":{"type":"object","class":"DateTime"},"atualizado":{"type":"object","class":"DateTime"}}
```

Optionally, you can get it through Service Locator

```php
$logger = $sm->get('LosLog\Log\StaticLogger');
$logger->debug("Test message");
```
The default logfile is data/log/static.log

#### Z-Ray
Z-Ray is an awesome resource from Zend Server that provides several information about the request, errors and the framework. It also has the possibility to add your own informations, so i added the StaticLogger messages to it.

More information can be seen [here](http://www.zend.com/en/products/server/z-ray-top-7-features).

Warning: The Z-Ray extensions works only on Zend Server 8 or greater.

##### Installation
To use the StaticLogger with Z-Ray, follow these steps:

1- Locate the zray extension directory inside the zend server. 

For example on Mac/Linux can be found on:
/usr/local/zend/var/zray/extesions

2- Create a directory called LosLog

3- Copy the zray.php and logo.png to this directory

The final result should be:
```
ls /usr/local/zend/var/zray/extensions/LosLog/
logo.png	zray.php
```

##### Usage
Just use the StatticLogger and the messages will appear inside a LosLog section of the Z-Ray bar.

Optionally, you can pass a "null" value to the file argument to use just the Z-Ray, without writing the message to a file:

```php
LosLog\Log\StaticLogger::save("Test message", null);
```
