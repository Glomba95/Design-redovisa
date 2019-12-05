Revision history
=================================


v2.0.1 (2019-04-24)
---------------------------------

* Fix scrutinizer configuration file to pass build.



v2.0.0 (2019-04-23)
---------------------------------

* Tag as v2.
* Add docker-compose.yml to test in docker.
* Update phpmd config to ignore $di.
* Remove Sensiolabs badge.
* Updated README.



v2.0.0-beta.3 (2018-08-29)
---------------------------------

* Remove use constant() to check for ANAX_WITH_SESSION in di config.



v2.0.0-beta.2 (2018-08-28)
---------------------------------

* Use constant() to check for ANAX_WITH_SESSION in di config.



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Update Makefile.



v2.0.0-alpha.3 (2018-08-10)
---------------------------------

* Fix return value from Session::debugInfo() when sessionis not defined.



v2.0.0-alpha.2 (2018-08-08)
---------------------------------

* Add unit tests.



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to go through code and remove some waist and potentially breaking backward compatibility.



v1.0.10 (2018-04-25)
---------------------------------

* Added Codacy badge.
* Adding service in config/di/session.php.
* Avoid starting the session within the $di callback initiating phase.
* README states that the session should be started using $session->start() within index.php.



v1.0.9 (2018-04-23)
---------------------------------

* Add badge for Codeclimate.
* Make pass scrutinizer by installing phpunit.



v1.0.8 (2018-04-16)
---------------------------------

* Add documentation to README.



v1.0.6 (2017-10-15)
---------------------------------

* Enable destroy() to work on cli, useful for unit testing.



v1.0.5 (2017-10-05)
---------------------------------

* Added debugInfo() to enable var_dump() of the session object.



v1.0.4 (2017-10-02)
---------------------------------

* Add a SessionInterface for type declarations.



v1.0.3 (2017-08-17)
---------------------------------

* Do not start the session when CLI (for example phpunit).



v1.0.2 (2017-08-17)
---------------------------------

* Only unset variable from session if it exists.



v1.0.1 (2017-06-26)
---------------------------------

* Added method to destroy session.



v1.0.0 (2017-05-04)
---------------------------------

* Extracted from anax to be its own module.
