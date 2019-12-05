Revision history
=================================



v2.0.4 (2018-11-16)
---------------------------------

* Remove Sensiolabs as validation tool.



v2.0.3 (2018-11-16)
---------------------------------

* Upgrade to new scrutinizer codestyle engine.
* Codestyle. Align code with docblock information.



v2.0.2 (2018-11-16)
---------------------------------

* Improve README with a Table of content.



v2.0.1 (2018-11-16)
---------------------------------

* Reduce code complexity in load().



v2.0.0 (2018-11-16)
---------------------------------

* Completed the README.md.
* Added test cases for README.md.
* Added test cases for compliance with PSR-11.



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Update Makefile.
* Update description in composer.json.



v2.0.0-alpha.9 (2018-08-10)
---------------------------------

* Fix build on travis/circleci.
* Remove composer.lock.



v2.0.0-alpha.8 (2018-08-10)
---------------------------------

* Removed config, moved to their modules.
* Add phpunit through composer.



v2.0.0-alpha.7 (2018-08-08)
---------------------------------

* Removed some service configuration files, moved to their modules.



v2.0.0-alpha.6 (2018-08-08)
---------------------------------

* Remove dependency to anax/configure.



v2.0.0-alpha.5 (2018-08-08)
---------------------------------

* Added unit tests.
* Added DI\\DIMagic.



v2.0.0-alpha.4 (2018-08-06)
---------------------------------

* @deprecated DI/DIFactoryConfigMagic.
* @deprecated DI/DIFactoryDefault.
* @deprecated DI/InjectionAwareInterface.
* @deprecated DI/InjectionAwareTrait.
* @deprecated DI/InjectionMagicTrait.



v2.0.0-alpha.3 (2018-08-06)
---------------------------------

* Use Psr/Container, psr11.
* @deprecated DI/Exception/DIExceptionInterface, replaced by psr11. 
* @deprecated DI/Exception/NotFoundExceptionInterface, replaced by psr11.
* @deprecated DI/DIInterface, replaced by psr11.



v2.0.0-alpha.2 (2018-08-01)
---------------------------------

* Update composer.json to PHP 7.2.
* Require anax/configure v2.0.0.0@alpha.



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to go through code and remove some waist and potentially breaking backward compatibility.



v1.0.10 (2018-05-24)
---------------------------------

* Add di services in individual files in config/di.



v1.0.9 (2017-10-15)
---------------------------------

* Add DIServiceSetBaseTrait and DIFactoryTest to ease unit testing of modules using di.



v1.0.8 (2017-09-26)
---------------------------------

* Minor rewrite of DIFactoryConfig::configure().



v1.0.7 (2017-09-26)
---------------------------------

* DIFactoryConfig now uses Config2Trait that allows to read configuration items from both files and from a directory containing files.



v1.0.6 (2017-09-25)
---------------------------------

* Add config/di_anax-site-develop.php as a more complete setup of services to work with anax development.



v1.0.5 (2017-09-14)
---------------------------------

* Make setDI return self.



v1.0.4 (2017-09-11)
---------------------------------

* Made DI instance variables protected instead of public.
* Remove commented section with magic methods from DI.
* Adding DIFactoryConfigMagic that uses DIMagicTrait.
* Adding testcases for DIFactoryConfigMagic.



v1.0.3 (2017-08-17)
---------------------------------

* Require specific DIInterface.
* Allow to activate service directly by configuration file setting.



v1.0.2 (2017-08-10)
---------------------------------

* Major rework doing integration test.



v1.0.1 (2017-08-09)
---------------------------------

* Rewrote DIFactoryDefault.
* Added DIFactoryConfig which reads services from configuration file.
* Adding sample klass App/AppDI which is InjectionAware.



v1.0.0 (2017-06-29)
---------------------------------

* Made it close to supporting PHP-FIG 11 Container, using DI instead of Container.
* First inofficial release, moved from Anax MVC.
