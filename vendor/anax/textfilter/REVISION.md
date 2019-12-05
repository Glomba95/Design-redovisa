Revision history
=================================



v1.2.5 (2019-04-01)
-----------------------------------

* Fix regular expressen by adding backslash for -: preg_replace(): Compilation failed: invalid range in character class at offset 17 in src/TextFilter/TTextUtilities.php on line 144



v1.2.4 (2018-08-14)
-----------------------------------

* Update Makefile.
* Update description in composer.json.



v1.2.3 (2018-08-14)
-----------------------------------

* Update di config to set include_base for frontmatter.



v1.2.2 (2018-08-13)
-----------------------------------

* Remove composer.lock from repo.
* Add config/di/textfilter.php to set up as service in di container.



v1.2.0 (2018-07-31)
-----------------------------------

* Add filters for frontmatter YAML, JSON and support include of files together with variable expressions.
* Adding filters "frontmatter", "variable" to support above.
* Marking older filters "jsonfrontmatter", "yamlfrontmatter" as obsolete for markdown parsing.
* Code structure to prepare move features to smaller standalone filter classes.



v1.1.1 (2018-06-26)
-----------------------------------

* Added shortcode for [CODEPEN].



v1.1.0 (2018-05-24)
-----------------------------------

* Upgrade to circleci v2.
* Upgrade Makefile with docker.
* Remove docker.lock.
* Use vendor/phpunit in scrutinizer.
* Move travis to 7.0 and above.
* Update composer to >=PHP7.0.
* Use semantic versioning in composer.json for dependencies and allow low version och packages.
* Upgrade phpunit testclass to support newer version of phpunit.
* Add info on semantic versioning in README.
* Add badge for codacy.
* Add badge for codeclimate.



v1.0.2 (2017-10-02)
-----------------------------------

* Add options to shortcode [YOUTUBE] for playlist, time, id and class.



v1.0.1 (2017-08-03)
---------------------------------

* Removed stray config dir.



v1.0.0 (2017-05-03)
---------------------------------

* Extracted from mos/ctextfilter to be an anax module anax/textfilter.
