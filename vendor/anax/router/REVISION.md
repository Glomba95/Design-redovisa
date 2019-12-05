Revision history
=================================

Notes for development v2.0.0*
---------------------------------

* Add route length (max, min) as a feature like type.
* Add forward to enable forwarding to another route handler, like MVC triads.
* (Use regexp to match route).



v2.0.0 (2019-04-01)
---------------------------------

* Release 2.0, documentation is still to be done.



v2.0.0-beta.9 (2018-11-23)
---------------------------------

* Enable router to call [class, method] using specific path to control argument types.



v2.0.0-beta.8 (2018-11-22)
---------------------------------

* Add name of method, as first argument sent to the catchAll-methods.



v2.0.0-beta.7 (2018-11-19)
---------------------------------

* Add support for controller method catchAll by request method.



v2.0.0-beta.6 (2018-09-25)
---------------------------------

* Add support for app style controllers injecting app.



v2.0.0-beta.5 (2018-08-15)
---------------------------------

* Update Makefile.



v2.0.0-beta.4 (2018-08-15)
---------------------------------

* Change description in composer.json.



v2.0.0-beta.3 (2018-08-15)
---------------------------------

* Add get method for internal error messages.



v2.0.0-beta.2 (2018-08-15)
---------------------------------

* Improve support for when the controller accepts a request.
* Use detailed exception messages in handleInternal



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Rename route/ to router/.
* Rmove router/999_404.php, dealt by with the internal route.
* Add test/controller to mount a controller.



v2.0.0-alpha.14 (2018-08-14)
---------------------------------

* Add method to get type of route handler, Route::getHandlerType().



v2.0.0-alpha.13 (2018-08-14)
---------------------------------

* Improve error handling when route handler is not callable.



v2.0.0-alpha.12 (2018-08-14)
---------------------------------

* Remove the 404 route, use internal instead.



v2.0.0-alpha.11 (2018-08-13)
---------------------------------

* Integrate with Anax Flat.



v2.0.0-alpha.10 (2018-08-10)
---------------------------------

* Fix correct urls in route dev/index.



v2.0.0-alpha.9 (2018-08-10)
---------------------------------

* Development routes without requirement on anax/{page,view}.
* Unit testing of config/route/710_development.php.



v2.0.0-alpha.8 (2018-08-09)
---------------------------------

* Load routes from file, programming style.
* Enhance test suite.



v2.0.0-alpha.7 (2018-08-08)
---------------------------------

* Fix: Testroute test/500 generate correct exception.



v2.0.0-alpha.6 (2018-08-08)
---------------------------------

* Fix: Use correct interface for $di.
* Add controller/action as handler.
* Add sample routes.



v2.0.0-alpha.5 (2018-08-07)
---------------------------------

* Rewrote major parts of Router and Route.
* Move anax/di to require-dev.



v2.0.0-alpha.4 (2018-08-03)
---------------------------------

* Rewrote src/Route/Router for newer configuration files.



v2.0.0-alpha.3 (2018-08-03)
---------------------------------

* @deprecated src/Route/RouterInjectable and removed it.
* Removed older route files.
* Removed direct router dependency to anax/configure.



v2.0.0-alpha.3 (2018-08-03)
---------------------------------

* Fix phpunit.
* Use v2.0.0@alpha from anax/common to start work to remove it.



v2.0.0-alpha.2 (2018-08-01)
---------------------------------

* Set requirement of PHP 7.2 in composer.json.
* Mark anax/common as obsolete and prepare to remove it and traces of $app constructions.
* Move composer.json require anax/di to suggests, eventually try to remove dependency to real version. 



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to go through code and remove some waist and potentially breaking backward compatibility.



v1.1.0 (2018-03-16)
---------------------------------

* Update to require PHP 7.0 and over.
* Move to circlesi v2.
* Add support for including $app centered routes through 'include'.
* Remove composer.lock.
* Show 404 when no route returns true nor does exit.



v1.0.15 (2017-09-28)
---------------------------------

* A route handler returning a non empty value will be the last handler to be called.
* Enhance error handling when the route callback is misconfigured.
* Move exceptions to subnamespace and own directory.
* Add ConfigurationException when configuration is incorrect.



v1.0.14 (2017-09-26)
---------------------------------

* Router::configure now uses Configure2Trait and can read from directory and files and support "sort".



v1.0.13 (2017-09-14)
---------------------------------

* Router::configure shall return self.
* Minor edit in docblock in Router.



v1.0.12 (2017-08-15)
---------------------------------

* Removing getName() and replacing with getInfo().
* Adding member info to the Route.
* Adding module anax/di as required in composer.json.
* Loading routes from configuration file.



v1.0.11 (2017-08-10)
---------------------------------

* Adding class Router as a DI enabled version.
* Add getName() for Route.



v1.0.10 (2017-08-10)
---------------------------------

* Add comment in route file to make 404 last in sequence.



v1.0.9 (2017-08-03)
---------------------------------

* Adding config/ with some default routes.



v1.0.8 (2017-06-27)
---------------------------------

* Fix unittest passing.
* Fix Route::checkPartAsArgument missing type vvariable.



v1.0.7 (2017-06-27)
---------------------------------

* Add Route::getRequestMethod() to show information on request method for route.
* Load routes from configuration file.
* Made RouterInjectable injectable with $app.



v1.0.6 (2017-06-27)
---------------------------------

* Modify type of integer argument when validatet using digit.



v1.0.5 (2017-04-24)
---------------------------------

* Adding documentation and testcases for documentation.
* Adding method RouterInjectable::always() as a default routehandler matching any route and request method.
* Rearrange methods to improve readability.
* Add docblocks for properties.
* Add support for adding several path rules with one route->add().



v1.0.4 (2017-04-13)
---------------------------------

* Add support for path/** to match subpaths.
* Fix composer validate PHP version in require-dev. 



v1.0.3 (2017-03-26)
---------------------------------

* Extending support for default routes to partly include "\*\*" and null, matching any route. 
* Support adding request method as string separated by |



v1.0.2 (2017-03-26)
---------------------------------

* Allow matching of several routehandlers having the same path.
* Add testcases.



v1.0.1 (2017-03-13)
---------------------------------

* Add arguments as part of route.
* Arguments can be validated as alpha, alphanum, digit, hex.
* Support different routes per request methods.



v1.0.0 (2017-03-07)
---------------------------------

* Making standalone without `$di`.
* Enhancing unittest.
* Adding exceptions.
* Cleanup makefile.
* Extracted from anax to be its own module.
