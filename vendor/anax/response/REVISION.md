Revision history
=================================



v2.0.1 (2019-11-13)
---------------------------------

* Add method getHeaders() for testability.
* Update Makefile for phpmd installation.



v2.0.0 (2019-04-05)
---------------------------------

* Update README.
* Test on PHP 7.3.



v2.0.0-beta.4 (2018-10-23)
---------------------------------

* Update Response::redirect to return self to allow chaining and allow user to directly call send().



v2.0.0-beta.3 (2018-09-10)
---------------------------------

* Rewrite Response::redirect to use Response-class and not header().



v2.0.0-beta.2 (2018-09-10)
---------------------------------

* Update config/di to support injection of $di.



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Update Makefile.



v2.0.0-alpha.8 (2018-08-09)
---------------------------------

* Update composer.json.



v2.0.0-alpha.7 (2018-08-09)
---------------------------------

* Enhance unit tests.



v2.0.0-alpha.6 (2018-08-08)
---------------------------------

* Add $di config file config/di/response.php.



v2.0.0-alpha.4 (2018-08-08)
---------------------------------

* Fix stylecode.



v2.0.0-alpha.3 (2018-08-08)
---------------------------------

* Added unit testing.



v2.0.0-alpha.2 (2018-08-08)
---------------------------------

* Adapt Anax Lite.



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to clean up waist and aline with release of Anax Lite v1.0.
* Fix year in LICENSE.txt.
* Ignore composer.lock.
* Update Makefile.
* Update to require PHP 7.2 in composer.json.
* Fix phpunit.



v1.0.8 (2017-10-15)
---------------------------------

* Return self from most methods, partly to ease unittesting in controllers.
* Refactor sendJson() to use send().
* Allow sending null to setStatsuCode() and ignore it.
* Check for headers already sent ignores when running in cli mode.
* Add getStatusCode() for test.



v1.0.7 (2017-09-11)
---------------------------------

* Add status codes 400, 405, 501, 418.
* Ignore phpmd exit expression in Response::redirect().



v1.0.6 (2017-08-17)
---------------------------------

* Do exit after redirect().
* Add utility methods through ResponseUtility.



v1.0.5 (2017-04-12)
---------------------------------

* Do not send headers if they are already sent, silently fail on send() and sendJson().
* Remove warning from composer.json, duplicate PHP version.



v1.0.4 (2017-03-13)
---------------------------------

* Added `JSON_UNESCAPED_SLASHES` to SendJson().



v1.0.3 (2017-03-10)
---------------------------------

* Remove `JSON_PRESERVE_ZERO_FRACTION` from SendJson().



v1.0.2 (2017-03-09)
---------------------------------

* Enable to use callable to set the body.



v1.0.1 (2017-03-07)
---------------------------------

* Corrections after test with anax-lite.



v1.0.0 (2017-03-07)
---------------------------------

* Extracted from anax to be its own module.
