<?php

namespace Anax\DI;

use Anax\DI\Exception\Exception;
use Psr\Container\ContainerInterface;

/**
 * Extending DI factory class with magic methods for getters to allow
 * easy usage to $di as $di->service, compare to a $app.
 */
class DIMagic extends DIFactoryConfig implements ContainerInterface
{
    use DIMagicTrait;
}
