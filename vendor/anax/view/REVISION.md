Revision history
=================================



v2.0.2 (2019-11-05)
---------------------------------

* Add views to render plain information in `plain/pre`.
* Update Makefile from commons.



v2.0.1 (2019-04-05)
---------------------------------

* Fix build on scrutinizer.



v2.0.0 (2019-04-05)
---------------------------------

* Updated README.
* Test towards PHP 7.3.



v2.0.0-beta.14 (2019-03-19)
---------------------------------

* Add view for stylechooser in view/anax/v2/stylechooser.



v2.0.0-beta.13 (2018-12-03)
---------------------------------

* Add view/anax/v2 for the blog.
* Re-adding view helper function wrapElementWithStartEnd.
* Re-adding view helper function wrapElementContentWithStartEnd.
* Fix missing tpl-variable in View/ViewCollection when using blog.



v2.0.0-beta.12 (2018-11-05)
---------------------------------

* Fix code style validation in file render.



v2.0.0-beta.11 (2018-11-05)
---------------------------------

* Fix code style validation (again).



v2.0.0-beta.10 (2018-11-02)
---------------------------------

* Fix code style validation.



v2.0.0-beta.8 (2018-10-23)
---------------------------------

* Add view helper redirect().
* Update layout to use flash message.



v2.0.0-beta.7 (2018-10-22)
---------------------------------

* Fix view data could not be merged.



v2.0.0-beta.6 (2018-10-22)
---------------------------------

* Adding default views to anax/v2.



v2.0.0-beta.5 (2018-10-15)
---------------------------------

* Send $data as argument to callable view.
* Add unit test for $data when setting view details.
* Add view function getPublishedDate().



v2.0.0-beta.4 (2018-08-30)
---------------------------------

* Added view helper e() as alias for htmlentities.



v2.0.0-beta.3 (2018-08-21)
---------------------------------

* Template file anax/v2/error/default.php should not echo debug info.



v2.0.0-beta.2 (2018-08-15)
---------------------------------

* Fix codestyle.
* Update description composer.json.



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Add view anax/v2/content/default.php.
* Remove stray src/View/ViewHelperTrait.



v2.0.0-alpha.8 (2018-08-15)
---------------------------------

* Add missing dev view for session increment and destroy.



v2.0.0-alpha.7 (2018-08-14)
---------------------------------

* Enhance dev view with route getHandlerType().



v2.0.0-alpha.6 (2018-08-14)
---------------------------------

* Update views to Anax oophp me.



v2.0.0-alpha.5 (2018-08-13)
---------------------------------

* Added views for error handler.



v2.0.0-alpha.4 (2018-08-08)
---------------------------------

* Removed obsolete code.



v2.0.0-alpha.3 (2018-08-03)
---------------------------------

* Use v2.0.0@alpha from anax/common to start work to remove it.



v2.0.0-alpha.2 (2018-08-01)
---------------------------------

* Fix year in LICENSE.txt.
* Use v2.0.0 of anax/configure.



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to clean up waist and aline with release of Anax Lite v1.0.
* Ignore composer.lock.
* Update Makefile.
* Update to require PHP 7.2 in composer.json.
* Fix phpunit.



v1.0.26 (2018-04-23)
---------------------------------

* Update config file for scrutinizer to install phpunit.



v1.0.26 (2018-04-23)
---------------------------------

* Update config file for scrutinizer to install phpunit.



v1.0.25 (2018-04-23)
---------------------------------

* Added views in view/anax/v1.



v1.0.24 (2018-03-14)
---------------------------------

* Add get_defined_functions() for view helper showEnvironment.



v1.0.23 (2017-10-17)
---------------------------------

* Include view helpers only once, needed for unit tests, fix #3.



v1.0.21 (2017-09-14)
---------------------------------

* Do not remove variable data when sending data to view.



v1.0.20 (2017-09-12)
---------------------------------

* Fix helper renderView and add tests.



v1.0.19 (2017-09-11)
---------------------------------

* Move book views to scaffold dir.



v1.0.18 (2017-09-10)
---------------------------------

* Improve formatting of view/default2/info.php.



v1.0.17 (2017-09-07)
---------------------------------

* Adding view/book template files.



v1.0.16 (2017-09-05)
---------------------------------

* Add showEnvironment() as view helper function.



v1.0.15 (2017-08-15)
---------------------------------

* Phpmd config to ignore short name for $di.
* Make anax/di and anax/configure part of composer.json to pass tests.



v1.0.14 (2017-08-14)
---------------------------------

* Bug: Double codeline in ViewRenderFile, fix #2.
* Change view info to use Route->getInfo().
* Add info about di loaded services.



v1.0.13 (2017-08-10)
---------------------------------

* Adding a version supporting DI using classes ViewCollection, View2 and config/viewdi.php.
* Update configfile view.php to include helper-functions for use in the view template files.



v1.0.12 (2017-06-27)
---------------------------------

* Replace view default1/404 with default1/http_status_code.
* Add own view dir to config path.
* Add navbar region to default1/layout.



v1.0.11 (2017-06-27)
---------------------------------

* Print route details in view/default1/info.



v1.0.10 (2017-06-26)
---------------------------------

* Add stylesheets to view/default1/layout.php.



v1.0.9 (2017-06-16)
---------------------------------

* Remove stray setApp from ViewContainer.
* Add new directory for views to prepare move from older versions.



v1.0.8 (2017-03-30)
---------------------------------

* Fix renderView() to supply $app.



v1.0.7 (2017-03-27)
---------------------------------

* Cleanup docblock.
* ViewContainer implements ConfigureInterface.



v1.0.6 (2017-03-17)
---------------------------------

* Update Makefile and configuration of phpcs.



v1.0.5 (2017-03-17)
---------------------------------

* Formatting and phpcs of view files.



v1.0.4 (2017-03-15)
---------------------------------

* Renamed all view files and removed .tpl.



v1.0.3 (2017-03-10)
---------------------------------

* Added default view library as example in view/default.
* Added config in config/view.php.



v1.0.2 (2017-03-09)
---------------------------------

* `ViewContainer:render()` returns void.



v1.0.1 (2017-03-09)
---------------------------------

* Remove duplicate $app from ViewContainer.
* Add sensiolabs badge.



v1.0.0 (2017-03-09)
---------------------------------

* Extracted from anax to be its own module.
* Rewritten without $di.
