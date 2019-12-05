<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * Test negative tests.
 */
class TextFilterSetGetFilterConfigurationFailTest extends TestCase
{
    /**
     * Check exception is thrown when setting configuration for non existing
     * filter.
     *
     * @expectedException Exception
     */
    public function testSetConfigForNoExistingFilter()
    {
        $filter = new TextFilter();
        $filter->setFilterConfig("no-such-filter", []);
    }



    /**
     * Check exception is thrown when getting configuration for non existing
     * filter.
     *
     * @expectedException Exception
     */
    public function testGetConfigForNoExistingFilter()
    {
        $filter = new TextFilter();
        $filter->getFilterConfig("no-such-filter", []);
    }
}
