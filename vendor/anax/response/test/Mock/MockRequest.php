<?php

namespace Anax\Response;

/**
 * Mocking a request class.
 */
class MockRequest
{
    public function getCurrentUrl()
    {
        return "current/url";
    }
}
