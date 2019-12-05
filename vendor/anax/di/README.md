Anax DI
==================================

[![Join the chat at https://gitter.im/canax/di](https://badges.gitter.im/canax/di.svg)](https://gitter.im/canax/di?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Stable Version](https://poser.pugx.org/anax/di/v/stable)](https://packagist.org/packages/anax/di)

[![Build Status](https://travis-ci.org/canax/di.svg?branch=master)](https://travis-ci.org/canax/di)
[![CircleCI](https://circleci.com/gh/canax/di.svg?style=svg)](https://circleci.com/gh/canax/di)

[![Build Status](https://scrutinizer-ci.com/g/canax/di/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/di/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/di/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/di/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/di/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/di/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/ff984b03c7e0421f5faa/maintainability)](https://codeclimate.com/github/canax/di/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c4802edf7cf5495e9be8dfbd5c116cb1)](https://www.codacy.com/app/mosbth/di?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/di&amp;utm_campaign=Badge_Grade)

Anax DI service container for dependency injection of framework services using creation and lazy loading of services.

The implementation of the container is compliant with [PHP-FIG 
PSR-11: Container interface](https://www.php-fig.org/psr/psr-11/)



Table of content
------------------

* [Install](#install)
* [Development](#development)
* [Basic usage](#basic-usage)
* [Shared service](#shared-service)
* [Lazy loading](#lazy-loading)
* [Usage in Anax](#usage-in-anax)
* [Anax initialization of services](#Anax-initialization-of-services)
* [Anax configuration of services](#Anax-configuration-of-services)
* [Dependency](#Dependency)
* [License](#License)

You can also read this [documentation online](https://canax.github.io/di/).



Install
------------------

You can install the module from [`anax/di` on Packagist](https://packagist.org/packages/anax/di) using composer.

```text
composer require anax/di
```



Development
------------------

To work as a developer you clone the repo and install the local environment through make. Then you can run the unit tests.

```text
make install
make test
```



Basic usage
------------------

This is the basic usage of the container.

First you create the container.

```php
// Create it
$di = new \Anax\DI\DI();

// Check its a PSR-11 interface
assert($di instanceof \Psr\Container\ContainerInterface);
```

Add services onto the container. 

```php
// Add a service
$di->set("response", "\Anax\Response\Response");

// Add a shared service
$di->setShared("view", "\Anax\View\ViewContainer");
```

Check if the service is loaded.

```php
// Check if service is loaded
if ($di->has("view")) {
    ; // the service is loaded
}
```

Get and use the service.

```php
// Get and use a service
$response = $di->get("response");
$response->addBody($body)->send();

// Same, without storing in a variable
$di->get("response")->addBody($body)->send();
```



Shared service
------------------

A shared service always return the same object. The object is instantiated once and then stored for the next wanting to use the service. There is only one instance af a shared service. The service is shared among all its users.

```php
// Add a shared service
$di->setShared("view", "\Anax\View\ViewContainer");

// Get two instances of the shared service
$view1 = $di->get("view");
$view2 = $di->get("view");
assert($view1 === $view2);
```

A service that is not shared will return a new instance each time it is get from the container.

```php
// Add a service
$di->set("response", "\Anax\Response\Response");

// Get two instances of the service
$response1 = $di->get("response");
$response2 = $di->get("response");
assert($response1 !== $response2);
```



Lazy loading
------------------

The services added to the container are not activated until accessed. They are lazy loaded to ensure they are only active when used.

You can see what services are loaded onto the container and you can see what services are activated.

```php
// Add services
$di->set("response", "\Anax\Response\Response");
$di->setShared("view", "\Anax\View\ViewContainer");

// Get one service
$response = $di->get("response");

// Check what services are loaded
implode(",", $di->getServices()); // response,view

// Check what services are active
implode(",", $di->getActiveServices()); // response
```



Usage in Anax
-------------------

In Anax all services are loaded during the bootstrap phase in the file `htdocs/index.php`. It looks like this.

```php
// Add all framework services to $di
$di = new Anax\DI\DIFactoryConfig();
$di->loadServices(ANAX_INSTALL_PATH . "/config/di");
```

The variable `$di` is the only global variable within the framework. The main dependency, as a global variable, is to the be accessable within the view template files and view helpers.



Anax initialization of services
-------------------

All the framework services are loaded from files in the directory `ANAX_INSTALL_PATH . "/config/di"`. Each file contains one service. A file can contain several services.

This is a example of a service configuration file for the service "request".

```php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "request" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Request\Request();
                $obj->init();
                return $obj;
            }
        ],
    ],
];
```

A service may contain a callback that initiates the service when it is loaded. The callback should return the initiated object.

The service can be defined through its class name when the service needs no extra initialisation.

```php
"callback" => "\Anax\Request\Request",
```

One common way to initialize the service is to inject `$di` into the service. This is an example of that.

```php
"response" => [
    "shared" => true,
    //"callback" => "\Anax\Response\Response",
    "callback" => function () {
        $obj = new \Anax\Response\ResponseUtility();
        $obj->setDI($this);
        return $obj;
    }
],
```



Anax configuration of services
-------------------

During the initialization phase some services might want to read additional configuration information. They can do so by using `anax/configuration`, like this.

First, lets have a look at the service "configuration".

```php
"configuration" => [
    "shared" => true,
    "callback" => function () {
        $config = new \Anax\Configure\Configuration();
        $dirs = require ANAX_INSTALL_PATH . "/config/configuration.php";
        $config->setBaseDirectories($dirs);
        return $config;
    }
],
```

It is a service to read configuration files from those directories that the framework specifies. It can be used like this, when creating other services that depends on their own configuration files.

```php
"session" => [
    "active" => defined("ANAX_WITH_SESSION") && ANAX_WITH_SESSION, // true|false
    "shared" => true,
    "callback" => function () {
        $session = new \Anax\Session\Session();

        // Load the configuration files
        $cfg = $this->get("configuration");
        $config = $cfg->load("session");

        // Set various settings in the $session
        // ...

        return $session;
    }
],
```

The configuration setting "active" states wether the service should be activated when its loaded, or if it should be lazy loaded.

In the callback, first the service "configuration" is retrieved from $di. Then it is used to read the configuration file named "session".

The actual configuration is returned from the configuration file "session" and can then be used to configure and setup the object, before returning it as a framework service.

A configuration file is in general stored by its service name in `ANAX_INSTALL_PATH . "/config/servicename.php"`. It should return something, like this.

```php
/**
 * Config-file for sessions.
 */
return [
    // Session name
    "name" => preg_replace("/[^a-z\d]/i", "", __DIR__),
];
```



Dependency
------------------

Using psr11 through `psr/container`.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2018 Mikael Roos, mos@dbwebb.se
```
