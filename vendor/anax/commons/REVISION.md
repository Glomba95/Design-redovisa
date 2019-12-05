Revision history
=================================



v2.0.3 (2019-11-21)
---------------------------------

* Fix helptext in Makefile to work in dir structure with space and åäö
* Remove cimage targets from Makefile.
* Update target theme i Makefile, to get files from build dir.



v2.0.2 (2019-11-01)
---------------------------------

* Update Makefile to latest version (phpmd installation).



v2.0.1 (2019-04-24)
---------------------------------

* Update to README.



v2.0.0 (2019-04-23)
---------------------------------

* Tag as v2.
* Added docker-compose.yml to enable docker-test.



v2.0.0-beta.13 (2018-11-20)
---------------------------------

* Add (disabled by default) support for anax/proxy in htdocs/index.php.



v2.0.0-beta.12 (2018-11-12)
---------------------------------

* Update docker-compose with cli ability.



v2.0.0-beta.11 (2018-11-05)
---------------------------------

* Add di as global identifier in test/config.php.



v2.0.0-beta.10 (2018-11-05)
---------------------------------

* Allow allow-unused-foreach-variables in phpmd.



v2.0.0-beta.9 (2018-11-05)
---------------------------------

* phpmd exclude e as function name.



v2.0.0-beta.8 (2018-11-05)
---------------------------------

* phpmd exclude e.
>>>>>>> 0093c20071bea6599f554635083270217e36337b



v2.0.0-beta.7 (2018-11-01)
---------------------------------

* phpcs exclude theme/.
* Removed empty lines in travis.



v2.0.0-beta.6 (2018-09-25)
---------------------------------

* Adding interface AppInjectableInterface for app-style injection.
* Adding trait AppInjectableTrait for app-style injection.



v2.0.0-beta.5 (2018-08-28)
---------------------------------

* Improve support for logging during production mode.



v2.0.0-beta.4 (2018-08-28)
---------------------------------

* Remove specific .htaccess variants, moved to anax-oophp-me.



v2.0.0-beta.3 (2018-08-28)
---------------------------------

* Fix error in config/commons.php, define wrap in string.



v2.0.0-beta.2 (2018-08-15)
---------------------------------

* Update description in composer.json.
* Update Makefile.



v2.0.0-beta.1 (2018-08-15)
---------------------------------

* Remove comment in Makefile for check bash.



v2.0.0-alpha.8 (2018-08-14)
---------------------------------

* Updating sample htdocs/js/main.js.
* Add htdocs/img/leaf.jpg.
* Makefile install phpmd from studentserver.



v2.0.0-alpha.7 (2018-08-13)
---------------------------------

* Add info about versioning in README.
* Add make target cimage-install.



v2.0.0-alpha.6 (2018-08-10)
---------------------------------

* Install phpunit through composer.
* Add sample htdocs/css.



v2.0.0-alpha.5 (2018-08-08)
---------------------------------

* Adding unit tests.



v2.0.0-alpha.4 (2018-08-06)
---------------------------------

* Adding Anax glue in src/Commons.
* Adding src/Commons/ContainerInjectable*.php



v2.0.0-alpha.3 (2018-08-03)
---------------------------------

* Add setting for ANAX_DEVELOPMENT and ANAX_PRODUCTION in config/error_reporting.php.



v2.0.0-alpha.2 (2018-08-02)
---------------------------------

* Principal decision, use this repo as glue to glue Anax components together.
* Add src/functions.php used by more or less all Anax modules.



v2.0.0-alpha.1 (2018-08-01)
---------------------------------

* Align versioning with Anax Lite.



v1.0.0 (2018-08-01)
---------------------------------

* Support scaffolding of Anax Lite v1.0.
