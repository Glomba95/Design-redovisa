<?php

namespace Anax\Cache;

use Psr\SimpleCache\InvalidArgumentException as InvalidArgumentExceptionInterface;

/**
 * Module specific exception.
 */
class InvalidArgumentException
    extends \Exception
    implements InvalidArgumentExceptionInterface
{

}
