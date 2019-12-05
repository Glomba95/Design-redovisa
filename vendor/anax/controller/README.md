Anax Controller
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/controller/v/stable)](https://packagist.org/packages/anax/controller)
[![Join the chat at https://gitter.im/canax/controller](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/canax/controller?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/controller.svg?branch=master)](https://travis-ci.org/canax/controller)
[![CircleCI](https://circleci.com/gh/canax/controller.svg?style=svg)](https://circleci.com/gh/canax/controller)

[![Build Status](https://scrutinizer-ci.com/g/canax/controller/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/controller/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/controller/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/controller/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/controller/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/controller/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/225b19ca0e961727e90b/maintainability)](https://codeclimate.com/github/canax/controller/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/65c7dcf6e04848fea7432bae8f1ce117)](https://www.codacy.com/app/mosbth/controller?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/controller&amp;utm_campaign=Badge_Grade)

Anax Controller is a collection of sample utility controllers, use them as scaffold (copy and modify) when creating your own controllers.

The controller can be useful code structure when building a site, as an alternative to ordinary route callbacks.



Table of content
------------------

* [Class, interface, trait](#class-interface-trait)
* [Exceptions](#exceptions)
* [App style or di style](#App-style-or-di-style)
* [Mounting a controller class on the router](#Mounting-a-controller-class-on-the-router)
* [How route path maps to controller/action](#How-route-path-maps-to-controller-action)
* [Returning values from a controller method](#Returning-values-from-a-controller-method)
* [Returning a page from the controller method](#Returning-a-page-from-the-controller-method)
* [Send arguments to a controller method](#Send-arguments-to-a-controller-method)
* [The initialize method](#The-initialize-method)
* [The catchAll method](#The-catchAll-method)
* [Additional controller class members](#Additional-controller-class-members)



Class, interface, trait
------------------

The following classes, interfaces and traits exists.

The following are used as is.

| Class, interface, trait                 | Description |
|-----------------------------------------|-------------|
| `Anax\Controller\DevelopmentController` | To aid debugging and visualising Anax internals. |
| `Anax\Controller\ErrorhandlerController` | General error handler controller presenting a error page when a route is not found or throws an exception, only for internal routes. |
| `Anax\Controller\FlatFileContentController` | A rough implementation of a flat file content controller reading markdown files from `content/` ant formatting into html and displaying in a page. |

The following are sample controllers that exists as samples, to copy and paste, when you build your own controller.

| Class, interface, trait                 | Description |
|-----------------------------------------|-------------|
| `Anax\Controller\SampleAppController`   | Implementation of a controller class that is injected with `$app` by the router. |
| `Anax\Controller\SampleController`      | Implementation of a controller class that is injected with `$di` by the router. |
| `Anax\Controller\SampleJsonController`  | Implementation of a controller class that is injected with `$di` by the router and returns json responses. |



Exceptions
------------------

There are no module specific exceptions.

It is common to use router exceptions when a route callback fails for some reason. Here are router exceptions that creates appropriate HTTP responses.

| Exception                                     | What    |
|-----------------------------------------------|---------|
| `Anax\Route\Exception\ForbiddenException`     | Results in a 304. |
| `Anax\Route\Exception\NotFoundException`      | Results in a 404. |
| `Anax\Route\Exception\InternalErrorException` | Results in a 500. |



App style or di style
------------------

A controller class might need access to the framework service container which is implemented in `$app` or `$di`. The router injects the service container automatically, based on if the controller class has implementet a known interface.

The router injects `$app` if the controller class implements `Anax\Commons\AppInjectableInterface`.

The router injects `$di` if the controller class implements `Anax\Commons\ContainerInjectableInterface`.

To implement this behaviour a controller class should use the utilities provided by the module `anax/commons` and create a controller class like this.

This is a app-style injection controller.

```
namespace Anax\Controller;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class SampleAppController implements AppInjectableInterface
{
    use AppInjectableTrait;
}
```

This is a di-style injection controller.

```
namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class SampleController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
}
```

The service container will then be available as `$this->app` or `$this->di`, depending on the interface used.



Mounting a controller class on the router
------------------

A controller class is mounted on a route path, a mount point, in the router. This is done by adding a configuration file to the router.

This is a sample of how the DevelopmentController is mounted on a mount point `dev/`. The file is stored as `600_development.php` in the directory `config/router/`.

```
/**
 * Routes to ease development and debugging.
 */
return [
    "routes" => [
        [
            "info" => "Development and debugging information.",
            "mount" => "dev",
            "handler" => "\Anax\Controller\DevelopmentController",
        ],
    ]
];
```

The configuration file states the mount point to be `dev` and the controller class to be `\Anax\Controller\DevelopmentController`.

You can review the content of the directory [`config/router`](config/router) to see more examples on how the controller class can be mounted to a mount point in the router.



How route path maps to controller/action
------------------

All requests below the mount point will be forwarded to the mounted controller to deal with. When the controller fails to deal with a request, the router will treat the path as a NotFoundException.

Each method in the controller can map a route path, a controller action. Here follows some samples on how a controller method (action) can be referenced through a route path.

Lets say that the controller `SampleAppController` is mounted on the mount point `app`, then the following would be true.

| Route path         | Controller method (action) |
|--------------------|----------------------------|
| `app`              | `indexAction()` |
| `app/index`        | `indexAction()`, usually you avoid using the `/index` part when creating an url to this action, thus only linking to `app/`. |
| `app/dump-app`     | `dumpAppAction()` |
| `app/info`         | `InfoAction()` |

The `Action` part is needed, it tells the router that this controller method should be treated as a controller action and mapped to the route path.

The method name is mapped to the route path, except the `Action` part which is removed. The method `infoAction()` is mapped to the route path `info`, the method `dumpAppAction()` is mapped to the route path `dump-action`.



Specify what HTTP method to support
------------------

You can add the HTTP method at the end of the method name, then the HTTP request method must also be mapped, before the controller method is matched by the router.

| Route path         | HTTP method | Controller method (action) |
|--------------------|-------------|----------------------------|
| `app/create`       | ANY         | `createAction()`           |
| `app/create`       | GET         | `createActionGet()`        |
| `app/create`       | POST        | `createActionPost()`       |



Define a controller method (action)
------------------

Here are some samples on how to create a controller method.

This method maps to the route path `app` or `app/index` on any HTTP request method.

```
/**
 * This is the index method action, it handles:
 * ANY METHOD mountpoint
 * ANY METHOD mountpoint/
 * ANY METHOD mountpoint/index
 *
 * @return string
 */
public function indexAction() : string
{
    // Deal with the action and return a response.
    return "Some kind of response";
}
```

This method maps to the route path `app/info` on GET HTTP request method.

```
/**
 * Add the request method to the method name to limit what request methods
 * the handler supports.
 * GET mountpoint/info
 *
 * @return string
 */
public function infoActionGet() : string
{
    // Deal with the action and return a response.
    return "Some kind of response";
}
```

The following two methods maps both the same route path `app/create`, the first maps only GET requests and the second maps only POST request.

```
/**
 * This sample method action it the handler for route:
 * GET mountpoint/create
 *
 * @return string
 */
public function createActionGet() : string
{
    // Deal with the action and return a response.
    return "Some kind of response";
}

/**
 * This sample method action it the handler for route:
 * POST mountpoint/create
 *
 * @return string
 */
public function createActionPost() : string
{
    // Deal with the action and return a response.
    return "Some kind of response";
}
```



Returning values from a controller method
------------------

Each controller method can return a response.

When nothing is returned, the router treats this as a no operation and continues to find the next handler that can deal with the request.

When any value is returned, the controller treats this as the handler successfully dealt with the request and promplty returns the response to the caller. 

The returned value is converted into an instance of the Anax response class.

When a controller method returns a string, the content of that string will be the response body. In the following example an page containing the body as `Some kind of response`.

```
public function infoActionGet() : string
{
    // Deal with the action and return a response.
    return "Some kind of response";
}
```

The controller method can return a JSON response by wrapping an array in an array. This is the converted into a JSON response by the Anax response class.

```
public function indexActionGet() : array
{
    // Deal with the action and return a response.
    $json = [
        "message" => "Some kind of response",
    ];
    return [$json];
}
```

Note that the type hinted return type now is `: array` instead of `: string`.

You can optionally send a response status code.

```
public function indexActionGet() : array
{
    // Deal with the action and return a response.
    $json = [
        "message" => "Some kind of response",
    ];
    return [$json, 200];
}
```



Returning a page from the controller method
------------------

A controller method can add views to a page and then render it. The render phase results in a response object which is returned. Here is a sample controller method that creates and returns a rendered page (app style).

```
/**
 * Display the stylechooser with details on current selected style.
 *
 * @return object
 */
public function indexAction() : object
{
    $title = "Stylechooser";

    $page = $this->app->page;
    $session = $this->app->session;

    $page->add("anax/v2/stylechooser/default", [
        "activeStyle" => $session->get("active-style", null),
    ]);

    return $page->render([
        "title" => $title,
    ]);
}
```

Note that the type hinted return type now is `: object` instead of `: string` or `: array`.



Send arguments to a controller method
------------------

A controller method can take arguments, the arguments are mapped from the route path, like this.

* `mountpoint/action/argument1/argument2/argument3`

So, if we want to create a controller method that replies to a route path like `app/view/<id>`, which could be `app/view/1` or `app/view/42`, then we create a method that takes one argument, like this.

```
/**
 * This sample method action takes one argument:
 * GET mountpoint/view/<value>
 *
 * @param mixed $id
 *
 * @return string
 */
public function viewActionGet($id) : string
{
    // Deal with the action and return a response.
    return "You are now viewing id: '$id'";
}
```

The method can take as many arguments as you need.

<!--
default arguments
variadic arguments
type hinting versus type checking
-->



The initialize method
------------------

Sometimes you have a need to bootstrap the controller, you have some common startup code that you want to execute before any of the actual action callbacks are carried out.

That code should _not_ be created in the constructor. Avoid using a constructor for the controller class. The reason is how the controller class is used and inspected by the router. The router must create a object of the controller, before it actually knows that the controller should be called. You should therefore avoid using a constructor and instead use the method `initialize()` 

The router will always call the controller method `initialize()`, if it is defined by the controller class.

The method can be defined like this.

```
/**
 * The initialize method is optional and will always be called before the
 * target method/action. This is a convienient method where you could
 * setup internal properties that are commonly used by several methods.
 *
 * @return void
 */
public function initialize() : void
{
    // Use to initialise member variables.
    $this->db = "active";
    // Use $this->app or $this->di to access the framework services.
}
```

Use the method to store the state in the class properties, for example create the connection to the database or create and initiate a set of objects.



The catchAll method
------------------

When the router fails to find a matching action, it will look for a method `catchAll()` and call it, if it is defined. This method can be used to handle error cases, or it can be used to create a controller which has a single method that deals with all incoming route paths, independent of the actual route path.

Here is a template to use when creating your own `catchAll()`.

```
/**
 * Adding an optional catchAll() method will catch all actions sent to the
 * router. You can then reply with an actual response or return void to
 * allow for the router to move on to next handler.
 * A catchAll() handles the following, if a specific action method is not
 * created:
 * ANY METHOD mountpoint/**
 *
 * @param array $args as a variadic parameter.
 *
 * @return mixed
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
public function catchAll(...$args)
{
    // Deal with the request and send an actual response, or not.
    return;
}
```

The `catchAll()` can take the arguments as a variadic variable `...$args` meaning that it can take any number of arguments and they are all stored in the array `$args`.

You can review the following real life examples of using the `catchAll()` in a controller.

* [`Anax\Controller\DevelopmentController`](src/Controller/DevelopmentController.php)
* [`Anax\Controller\ErrorController`](src/Controller/ErrorController.php)
* [`Anax\Controller\FlatFileContentController`](src/Controller/FlatFileContentController.php)

There is also a more powerful module for flat file content in [`anax/content`](https://github.com/canax/content), you can review its controller here.

* [`Anax\Content\FileBasedContentController`](https://github.com/canax/content/blob/master/src/Content/FileBasedContentController.php)



Additional controller class members
------------------

You can furthermore create any public, protected or private method and property within the controller class, besides these mentioned special controller methods.

This makes it possible to add common code, used in several controller actions, within class methods. Thus the controller enables some code reuse and allows for a proper code structure.


<!--
Internal router methods
------------------
-->


Thin or fat controller
------------------

The general idea is to have a thin controller, that is, small amount of code that glues together the framework with the model classes.

When your controller grows, take some time to thick if the code can be broken out into other (model) classes that are used by the controller.

A fat controller is the opposite of a thin controller. It has a large amount of code and logic in it.

Avoid fatness and break out your code in small usable classes, each class having a obvious and clear responsibility.



How to unit test a controller
------------------

A controller class can be unit tested like any other class. However, since a controller class is a glue between the framwork and your application (model) classes, it might need some preparations and even som fixtures, to make it easier.

A controller method that does not use the framework service container is pretty straightforward to test. Here is a sample of such a unit test (phpunit).

```
/**
 * Call the controller index action.
 */
public function testIndexAction()
{
    // Create and initiate the controller
    $this->controller = new SampleAppController();
    $this->controller->setApp($app);
    $this->controller->initialize();

    // Carry out the test
    $res = $this->controller->indexAction();
    $this->assertIsString($res);
    $this->assertStringEndsWith("active", $res);
}
```

When you have a controller needing the service container you might want to use a `setUp()` to setup the controller like it would be set up by the router.

```
/**
 * Setup the controller, before each testcase, just like the router
 * would set it up.
 */
protected function setUp(): void
{
    // Init service container $di to contain $app as a service
    $di = new DIMagic();
    $app = $di;
    $di->set("app", $app);

    // Create and initiate the controller
    $this->controller = new SampleAppController();
    $this->controller->setApp($app);
    $this->controller->initialize();
}



/**
 * Call the controller index action.
 */
public function testIndexAction()
{
    $res = $this->controller->indexAction();
    $this->assertIsString($res);
    $this->assertStringEndsWith("active", $res);
}
```

Then you can add more test cases for each controller action, and if you have some internal controller methods or fail testing you want to add unit tests for.

As an example, you might want to review the test class [`SampleAppControllerTest`](test/Controller/SampleAppControllerTest) that tests the controller class [`SampleAppController`](src/Controller/SampleAppController). it contains samples usable when creating your own test class for a controller.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
