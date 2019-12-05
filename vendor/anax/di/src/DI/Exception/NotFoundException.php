<?php

namespace Anax\DI\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Exception when service is not found in DI.
 */
class NotFoundException extends \Exception implements NotFoundExceptionInterface
{

}
