Anax Router
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/router/v/stable)](https://packagist.org/packages/anax/router)
[![Join the chat at https://gitter.im/canax/router](https://badges.gitter.im/canax/router.svg)](https://gitter.im/canax/router?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/router.svg?branch=master)](https://travis-ci.org/canax/router)
[![CircleCI](https://circleci.com/gh/canax/router.svg?style=svg)](https://circleci.com/gh/canax/router)

[![Build Status](https://scrutinizer-ci.com/g/canax/router/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/router/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/router/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/router/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/router/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/router/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/03322fa7864ad24a9b2a/maintainability)](https://codeclimate.com/github/canax/router/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/03950aec85654aeeb443e9c6cb972d1c)](https://www.codacy.com/app/mosbth/router?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/router&amp;utm_campaign=Badge_Grade)

Anax Router module.

A standalone router supporting request methods and dynamic routes matching, extracting and validating arguments from path.

The router will try matching routes by the order they were added and execute all matching routes, one after the other.

Use `exit()` to prevent further routes from being matched.



Install
------------------

```bash
$ composer require anax/router
```



Usage
------------------



### Add some routes with handlers

```php
use Anax\Route\Router;

$router = new Router();

$router->add("", function () {
    echo "home ";
});

$router->add("about", function () {
    echo "about ";
});

$router->add("about/me", function () {
    echo "about/me ";
});

// try it out
$router->handle("");
$router->handle("about");
$router->handle("about/me");
// home about about/me
```



### Add multiple routes with one handler

Add multiple routes, through an array of rules, sharing a handler.

```php
$router = new Router();

$router->add(["info", "about"], function () {
    echo "info or about - ";
});

// try it out
$router->handle("info");
$router->handle("about");
// info or about - info or about -
```



### Add a default route

This route will match any path.

```php
$router = new Router();

$router->always(function () {
    echo "always ";
});

// try it out using some paths
$router->handle("info");
$router->handle("about");
// always always
```



### Add internal routes for 404, 403 and 500 error handling

Add an internal route that is called when no route can be matched.

```php
$router = new Router();

$router->addInternal("404", function () {
    echo "404 ";
});

$router->add("about", function () {
    echo "about ";
});

// try it out using some paths
$router->handle("whatever");
// 404
```

You can add internal routes to deal with 403 and 500. These routes will handle uncaught exceptions thrown within a route handler.

The 403 internal route that is catching exception of type `ForbiddenException`.

```php
$router->addInternal("403", function () {
    echo "403 ";
});

$router->add("login", function () {
    throw new ForbiddenException();
});

// try it out using some paths
$router->handle("login");
// 403
```

The 500 internal route that is catching exception of type `InternalErrorException`.

```php
$router->addInternal("500", function () {
    echo "500 ";
});

$router->add("calculate", function () {
    throw new InternalErrorException();
});

// try it out using some paths
$router->handle("calculate");
// 500
```




### Add a common route for any item below subpath using *

This route will match any item on the same level as `about/*`.

```php
$router = new Router();

$router->addInternal("404", function () {
    echo "404 ";
});

$router->add("about/*", function () {
    echo "about ";
});

// try it out using some paths
$router->handle("about");
$router->handle("about/me");
$router->handle("about/you");
$router->handle("about/some/other"); // no match
// about about about 404
```



### Add a common route for any item below subpath using **

This route will match any item below `about/**`, even subdirs.

```php
$router = new Router();

$router->add("about/**", function () {
    echo "about ";
});

// try it out using some paths
$router->handle("about");
$router->handle("about/me");
$router->handle("about/you");
$router->handle("about/some/other");
// about about about about
```



### Part of path as arguments to the route handler

You can send a part of the route as an argument to the handler. This makes a route handler more flexible and dynamic.

```php
$router = new Router();

$router->addInternal("404", function () {
    echo "404 ";
});

$router->add("about/{arg}", function ($arg) {
    echo "$arg ";
});

ob_start();
// try it out using some paths
$router->handle("about");            // not matched
$router->handle("about/me");
$router->handle("about/you");
$router->handle("about/some/other"); // not matched
// 404 me you 404
```

You can send multiple arguments.

```php
$router = new Router();

$router->add(
    "post/{year}/{month}/{day}",
    function ($year, $month, $day) {
        echo "$year-$month-$day, ";
    }
);

// try it out using some paths
$router->handle("post/2017/03/07");
$router->handle("post/1990/06/20");
// 2017-03-07, 1990-06-20,
```



### Type checking of arguments

Apply type checking to the arguments to restrict a the routes being matched.

```php
$router = new Router();

$router->addInternal("404", function () {
    echo "404, ";
});

$router->add(
    "post/{year:digit}/{month:digit}/{day:digit}",
    function ($year, $month, $day) {
        echo "$year-$month-$day, ";
    }
);

$router->add(
    "post/{year:digit}/{month:alpha}/{day:digit}",
    function ($year, $month, $day) {
        echo "$day $month $year, ";
    }
);

// try it out using some paths
$router->handle("post/2017/03/seven");
$router->handle("post/2017/03/07");
$router->handle("post/1990/06/20");
$router->handle("post/1990/june/20");
// 404, 2017-03-07, 1990-06-20, 20 june 1990,
```

For type checking is digit, alpha, alphanum and hex supported (see [ctype](http://php.net/manual/en/ref.ctype.php) for details).



### Routes per request method

A route can be setup to match only one request method.

```php
$router = new Router();

$router->any(["GET"], "about", function () {
    echo "GET ";
});

$router->any(["POST"], "about", function () {
    echo "POST ";
});

$router->any(["PUT"], "about", function () {
    echo "PUT ";
});

$router->any(["DELETE"], "about", function () {
    echo "DELETE ";
});

// try it out using some paths
$router->handle("about", "GET");
$router->handle("about", "POST");
$router->handle("about", "PUT");
$router->handle("about", "DELETE");
// GET POST PUT DELETE
```

A route can also match several request methods.

```php
$router = new Router();

$router->any(["GET", "POST"], "about", function () {
    echo "GET+POST ";
});

$router->any("PUT | DELETE", "about", function () {
    echo "PUT+DELETE ";
});

// try it out using some paths
$router->handle("about", "GET");
$router->handle("about", "POST");
$router->handle("about", "PUT");
$router->handle("about", "DELETE");
// GET+POST GET+POST PUT+DELETE PUT+DELETE
```



Dependency
------------------

These are the dependencies to other modules.

| Module         | What    |
|----------------|---------|
| `anax/commons` | Using Anax\\Commons\\ContainerInjectableInterface. |
| `anax/commons` | Using Anax\\Commons\\ContainerInjectableTrait. |



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
