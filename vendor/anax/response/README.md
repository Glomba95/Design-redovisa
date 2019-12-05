Anax Response
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/response/v/stable)](https://packagist.org/packages/anax/response)
[![Join the chat at https://gitter.im/mosbth/anax](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/canax?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/response.svg?branch=master)](https://travis-ci.org/canax/response)
[![CircleCI](https://circleci.com/gh/canax/response.svg?style=svg)](https://circleci.com/gh/canax/response)

[![Build Status](https://scrutinizer-ci.com/g/canax/response/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/response/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/response/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/response/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/response/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/response/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/6d10c15d13230b4af06f/maintainability)](https://codeclimate.com/github/canax/response/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7ad1b537c0564ad6a1e43fa93e594ea6)](https://www.codacy.com/app/mosbth/response?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/response&amp;utm_campaign=Badge_Grade)

Anax Response module to send HTTP responses.

The module is used to send a HTTP response from the Anax framework, including status code, headers, and body.



Table of content
------------------

* [Class, interface, trait](#class-interface-trait)
* [Exceptions](#exceptions)
* [Configuration file](#configuration-file)
* [DI service](#di-service)
* [General usage within the Anax framework](#general-usage-within-the-Anax-framework)
* [Access as framework service](#access-as-framework-service)
* [Create, init and use an object](#create-init-and-use-an-object)



Class, interface, trait
------------------

The following classes, interfaces and traits exists.

| Class, interface, trait            | Description |
|------------------------------------|-------------|
| `Anax\Response\Response`           | Wrapper class for response details and related. |
| `Anax\Response\ResponseUtility`    | Extends the Request class to be injectable with `$id` to enable easy to use redirect methods. |



Exceptions
------------------

Module specific exceptions are thrown through `Anax\Response\Exception`.



Configuration file
------------------

There is no configuration file for this module.



DI service
------------------

The module is created as a framework service within `$di`. You can see the details in the configuration file [`config/di/response.php`](config/di/seponse.php).

It can look like this.

```php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "response" => [
            "shared" => true,
            //"callback" => "\Anax\Response\Response",
            "callback" => function () {
                $obj = new \Anax\Response\ResponseUtility();
                $obj->setDI($this);
                return $obj;
            }
        ],
    ],
];
```

1. The object is created as a shared resource.
1. DI is injected into the module, when using ResponseUtility.

The service is lazy loaded and not created until it is used.



General usage within the Anax framework
------------------

The response service is a mandatory service within the Anax framework and it is the first service used when handling a request to the framework.

Here is the general flow for receiving a request, mapping it to a route and returning a response. This is found in the frontcontroller `htdocs/index.php` of an Anax installation.

```php
// Leave to router to match incoming request to routes
$response = $di->get("router")->handle(
    $di->get("request")->getRoute(),
    $di->get("request")->getMethod()
);
// Send the HTTP response with headers and body
$di->get("response")->send($response);
```

The request is used to get the request method and the route path, these are used by the router service to find a callback for the route. Each callback can then return a response which is sent through the response service.



Access as framework service
------------------

You can access the module as a framework service.

```php
# $app style
$app->response->redirectSelf();

# $di style, two alternatives
$di->get("response")->redirectSelf();

$response = $di->get("response");
$response->redirectSelf();
```



Create, init and use an object
------------------

This is how the object can be created. This is usually done within the framework as a sevice in `$di`.

```php
# Create an object
response = new \Anax\Response\Response();

# Send a response
response->send("Some response");
response->sendJson(["key" => "Some value"]);
response->redirect($absoluteUrl);
```

The added benefit of using ResponseUtility is that this class is injected with DI and makes it easier to use the redirect methods for urls within the framework.

```php
# Create an object
response = new \Anax\Response\ResponseUtility();

# Do a redirect
response->redirect("game/init");
response->redirectSelf();
```



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
