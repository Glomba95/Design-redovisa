Anax Session
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/session/v/stable)](https://packagist.org/packages/anax/session)
[![Join the chat at https://gitter.im/canax/session](https://badges.gitter.im/canax/session.svg)](https://gitter.im/canax/session?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/session.svg?branch=master)](https://travis-ci.org/canax/session)
[![CircleCI](https://circleci.com/gh/canax/session.svg?style=svg)](https://circleci.com/gh/canax/session)

[![Build Status](https://scrutinizer-ci.com/g/canax/session/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/session/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/session/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/session/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/session/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/session/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/564636378b4f1c14132f/maintainability)](https://codeclimate.com/github/canax/session/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4e942efed3f741db94c027d2f145d129)](https://www.codacy.com/app/mosbth/session?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/session&amp;utm_campaign=Badge_Grade)

Anax Session module, for wrapping the session and providing useful helpers related to the session.

The Session module is prepared wo be used for unit testing when run in CLI environment.



Table of content
------------------

* [Class, interface, trait](#class-interface-trait)
* [Exceptions](#exceptions)
* [Configuration file](#configuration-file)
* [DI service](#di-service)
* [Access as framework service](#access-as-framework-service)
* [Start the session](#start-the-session)
* [Work with session variables](#work-with-session-variables)
* [Session flash messages](#session-flash-messages)
* [Debug the session](#debug-the-session)
* [Destroy the session](#destroy-the-session)



Class, interface, trait
------------------

The following classes, interfaces and traits exists.

| Class, interface, trait            | Description |
|------------------------------------|-------------|
| `Anax\Session\Session`             | Wrapper class for sessions. |
| `Anax\Session\SessionInterface`    | Interface to implement for classes what want to wrap the session. |



Exceptions
------------------

There are no module specific exceptions.



Configuration file
------------------

This is a sample configuration file, it is usually stored in `config/session.php`.

```php
/**
 * Config-file for sessions.
 */
return [
    // Session name
    "name" => preg_replace("/[^a-z\d]/i", "", __DIR__),
    //"name" => preg_replace("/[^a-z\d]/i", "", ANAX_APP_PATH),
];
```



DI service
------------------

The session is created as a framework service within `$di`. The following is a sample on how the session service is created.

```php
/**
 * Creating the session as a $di service.
 */
return [
    // Services to add to the container.
    "services" => [
        "session" => [
            "active" => defined("ANAX_WITH_SESSION") && ANAX_WITH_SESSION, // true|false
            "shared" => true,
            "callback" => function () {
                $session = new \Anax\Session\Session();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("session");

                // Set session name
                $name = $config["config"]["name"] ?? null;
                if (is_string($name)) {
                    $session->name($name);
                }

                $session->start();

                return $session;
            }
        ],
    ],
];
```

The session can always be active, in the default configuration file this is depending on the PHP define `ANAX_WITH_SESSION` which is usually set in `config/commons.php` which is available from the module `anax/commons`.

The session is always started when `ANAX_WITH_SESSION` is defined and `true`. 

This is how the callback works, when it creates the session service.

1. The object is created.
1. The configuration file, usually `config/session.php`, is read.
1. The session is named.
1. The session is created.

The service is lazy loaded and not created until it is used. However, it is always loaded when `"active" => true`.



Access as framework service
------------------

You can access the module as a framework service.

```php
# $app style
$app->session->start();

# $di style, two alternatives
$di->get("session")->start();

$session = $di->get("session");
$session->start();
```



Start the session
------------------

The recommended way, and the default behaviour, is to start the session by defining `ANAX_WITH_SESSION` and set it to `true`. This is usually done in `config/commons.php` which source is available in the module `anax/commons`.

Setting `ANAX_WITH_SESSION` to `false` means that the session is not started by default.

As an alternative, you can start the session anywhere by activating (using) the di service. A good place to do this is in the frontkontroller `index.php`, after you have created `$di` (and `$app`). The session will then be available for all page requests.

The session will not start when running in CLI (for example PHPUnit), however, it will still be active and usable as means of unit testing.



Work with session variables
------------------

There are helpers to use when reading and setting values in the session.

```php
# Check if a key is set in the session
$app->session->has($key);

# Get a value from the session, null if it is not set.
$app->session->get($key);

# Get a value from the session or get the default value.
$app->session->get($key, $default);

# Set a value in the session, associated with a key.
$app->session->set($key, $value);

# Delete a key from the session.
$app->session->delete($key);
```



Session flash messages
------------------

There is feature useful for flash messages. A value is retrieved from the session and is then deleted.

```php
# Get the value from the session and then delete its key, default is null.
$app->session->getOnce($key);

# Specify your own default value when key does not exists.
$app->session->getOnce($key, $default);
```



Debug the session
------------------

You can var_dump the session object and it will print out the content of `$_SESSION`.

```php
# Debug the session and review its content through var_dump.
var_dump($app->session);
```



Destroy the session
------------------

You can destroy the session which might be useful during development.

```php
# Destroy the session.
$app->session->destroy();
```



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
