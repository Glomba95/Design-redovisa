Anax Commons
========================

[![Latest Stable Version](https://poser.pugx.org/anax/commons/v/stable)](https://packagist.org/packages/anax/commons)
[![Join the chat at https://gitter.im/canax/commons](https://badges.gitter.im/canax/commons.svg)](https://gitter.im/canax/commons?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/commons.svg?branch=master)](https://travis-ci.org/canax/commons)
[![CircleCI](https://circleci.com/gh/canax/commons.svg?style=svg)](https://circleci.com/gh/canax/commons)

[![Build Status](https://scrutinizer-ci.com/g/canax/commons/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/commons/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/commons/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/commons/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/commons/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/commons/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/8705e9bc0a597e6dfb9a/maintainability)](https://codeclimate.com/github/canax/commons/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c3d60f33c0b947a3af127788e800b402)](https://www.codacy.com/app/mosbth/commons?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/commons&amp;utm_campaign=Badge_Grade)

A place to store common files to have one central copy of the latest version of the file.

This repo is used by scaffolding processes which copies files to set upp fresh installations. The repo also contains development files and various configurations files for external build tools.

The repo also contains commonly used sourcecode like interfaces and traits, such code that is usually shared between several Anax modules.



Functions
------------------

The module contains a set of functions that are used by several modules. The functions are available in [`src/functions.php`](src/functions.php) and they are autoloaded when this module is used.



Class, interface, trait
------------------

The following classes, interfaces and traits exists.

| Class, interface, trait               | Description |
|---------------------------------------|-------------|
| `Anax\Commons\AppInjectableInterface` | For classes that wants to be injectable by `$app`. |
| `Anax\Commons\AppInjectableTrait`     | Implementation of the interface. |
| `Anax\Commons\ContainerInjectableInterface` | For classes that wants to be injectable by `$di`. |
| `Anax\Commons\ContainerInjectableTrait`     | Implementation of the interface. |



Exceptions
------------------

There are no module specific exceptions supplied by this module.



App injectable
------------------

When a class wants to be injectable with `$app` it should implement the interface [`AppInjectableInterface`](src/Commons/AppInjectableInterface.php) which can be implemented by using the trait [`AppInjectableTrait`](src/Commons/AppInjectableTrait.php).

Here is a sample when used together with a controller which can be injected with `$app`.

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
```

Review the source of the actual interface and the trait to investigate their implementation.



Container injectable
------------------

When a class wants to be injectable with the service container `$di` it should implement the interface [`ContainerInjectableInterface`](src/Commons/ContainerInjectableInterface.php) which can be implemented by using the trait [`ContainerInjectableTrait`](src/Commons/ContainerInjectableTrait.php).

Here is a sample when used together with a controller which can be injected with `$di`.

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
```

Review the source of the actual interface and the trait to investigate their implementation.



Versioning
------------------

We use [Semantic Versioning 2.0.0](https://semver.org/spec/v2.0.0.html).



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
