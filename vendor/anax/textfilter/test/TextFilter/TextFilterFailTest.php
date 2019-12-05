<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * Test negative tests.
 */
class TextFilterFailTest extends TestCase
{
    /**
     * Check exception is thrown when filter does not exist.
     *
     * @expectedException Exception
     */
    public function testNoSuchFilterException()
    {
        $filter = new TextFilter();
        $filter->doFilter("void", "no-such-filter");
    }
}
