Anax Configure
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/configure/v/stable)](https://packagist.org/packages/anax/configure)
[![Join the chat at https://gitter.im/canax/configure](https://badges.gitter.im/canax/configure.svg)](https://gitter.im/canax/configure?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/configure.svg?branch=master)](https://travis-ci.org/canax/configure)
[![CircleCI](https://circleci.com/gh/canax/configure.svg?style=svg)](https://circleci.com/gh/canax/configure)

[![Build Status](https://scrutinizer-ci.com/g/canax/configure/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/configure/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/configure/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/configure/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/configure/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/configure/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/cda1f1d88d8c7f4aea49/maintainability)](https://codeclimate.com/github/canax/configure/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/79d0eab0f627424da588b4b39cfc9f17)](https://www.codacy.com/app/mosbth/configure?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/configure&amp;utm_campaign=Badge_Grade)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/067df5c1-e2f6-4f2e-b479-79cfe511ae7c/mini.png)](https://insight.sensiolabs.com/projects/067df5c1-e2f6-4f2e-b479-79cfe511ae7c)

Read configuration files for Anax and Anax modules.

The configuration files for a module can be stored in one of several base directories. The Configuration class scans all valid base directories and stops at the first one containing configuration items.

The configuration items can be stored in a file, and/or in a directory containing several files. All files are loaded and combined into one array containing each bits and piece of the module configuration.



Install
------------------

```bash
$ composer require anax/configure
```



Related classes
------------------

These are the classes included in this module, and their primary purpose.

| Class         | Purpose |
|---------------|---------|
| Configuration | Read configuration files and store in array. |



Use as DI service
------------------

You can create the Configuration object as a $di service. That is how Anax does it.



Sample usage
------------------

Create a object that can read configuration files.

First create the object and point it to a set of directories.

```php
$config = new \Anax\Configure\Configuration();
$dirs = ["path1", "path2"];
$config->setBaseDirectories($dirs);
```

Now use the objekt to find and load configuration files for an item, in the example we are using the module "router" as an example.

```php
$config = $di->get("configuration")->load("route")
```

The `$config` will now contain the configuration items found from the file, or files. You could now provide the array to the module or object that should use it.



A configuration file
------------------

A configuration file for a module "route" is any, or a combination of the following.

| File/path     | What    |
|---------------|---------|
| `route.php`   | A file. |
| `route/*.php` | Several files. |

The files should return a value, which will be its contribution to the configuration.



The configuration array
------------------

The resulting configuration array looks like this, still using "route" as example for the module name.

```php
$config = [
    "file" => filename for route.php,
    "config" => result returned from route.php,
    "items" => [
        [
            "file" => filename for route/file1.php,
            "config" => result returned from route/file1.php,
        ],
        [
            "file" => filename for route/file2.php,
            "config" => result returned from route/file2.php,
        ],
    ].
];
```

Tha "route" module can then decide on how to use the actual configuration details.



Dependency
------------------

There are no dependencies.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2018 Mikael Roos, mos@dbwebb.se
```
