Revision history
=================================


v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Update Makefile.
* Update description in composer.json.



v2.0.0-alpha.7 (2018-08-08)
---------------------------------

* Fix only naming a config file and no dir.



v2.0.0-alpha.6 (2018-08-08)
---------------------------------

* Add setMapping() to ease test to map specific file to an item.



v2.0.0-alpha.5 (2018-08-03)
---------------------------------

* Add filename to each loaded configuration file, to ease debugging.



v2.0.0-alpha.4 (2018-08-03)
---------------------------------

* Do not use keys when loading files from dir.



v2.0.0-alpha.3 (2018-08-03)
---------------------------------

* In composer.json require anax/commons.
* Add dependency section in README.



v2.0.0-alpha.2 (2018-08-02)
---------------------------------

* Add class Anax/Configure/Configuration to read configuration from files and directories. Lift this feature out from the Anax modules.
* Remove config/error_reporting.php.
* Add codeclimate.yml configuration file.
* Add config/configuration.php to hold paths to base directories for the configuration.
* Add config/di/configuration.php to setup as service in $di.
* @deprecated src/Configure/Configure2Trait.php
* @deprecated src/Configure/ConfigureTrait.php
* @deprecated src/Configure/ConfigureInterface.php



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Prepare to clean up waist and aline with release of Anax Lite v1.0.
* Update Makefile.
* Update to require PHP 7.2 in composer.json.
* Fix phpunit.



v1.0.5 (2018-03-16)
---------------------------------

* When config file returns scalar value, wrap in array.



v1.0.4 (2017-09-26)
---------------------------------

* Configure2Trait can read configuration from file, or directory, or both.



v1.0.3 (2017-09-26)
---------------------------------

* Adding Configure2Trait that also reads details from a directory having the same name as the config file, minus the extension.



v1.0.2 (2017-08-17)
---------------------------------

* Allow absolute path to config file.



v1.0.1 (2017-08-10)
---------------------------------

* Adding utility getConfig() in trait.



v1.0.0 (2017-05-04)
---------------------------------

* Moved from anax/common to own repository.
