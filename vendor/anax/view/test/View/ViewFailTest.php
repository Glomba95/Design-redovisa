<?php

namespace Anax\View;

use PHPUnit\Framework\TestCase;

/**
 * Negative tests for view.
 */
class ViewFailTest extends TestCase
{
    /**
     * Test
     *
     * @expectedException \Anax\View\Exception
     */
    public function testInvalidViewType()
    {
        $view = new View();
        $view->set("void", [], 0, "not-valid-typ");
        $view->render();
    }
}
