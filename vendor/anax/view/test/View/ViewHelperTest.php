<?php

namespace Anax\View;

use PHPUnit\Framework\TestCase;

/**
 * Views.
 */
class ViewHelperTest extends TestCase
{
    /**
     * Provider for test values
     *
     * @return array
     */
    public function providerClassList()
    {
        return [
            [
                [ null ],
                "class=\"\"",
            ],
            [
                [ "a" ],
                "class=\"a\"",
            ],
            [
                [ "a", "b" ],
                "class=\"a b\"",
            ],
            [
                [ "a", "b", ["c", "d"] ],
                "class=\"a b c d\"",
            ],
            [
                [ [], "a" ],
                "class=\"a\"",
            ],
        ];
    }



    /**
     * Test
     *
     * @dataProvider providerClassList
     */
    public function testClassList($args, $exp)
    {
        $res = classList(...$args);
        $this->assertEquals($exp, $res, "Classlist did not match.");
    }
}
