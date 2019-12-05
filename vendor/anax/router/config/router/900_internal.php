<?php
/**
 * Internal routes for error handling to show response when internal
 * exceptions are thrown.
 */
return [
    // Path where to mount the routes, is added to each route path.
    "mount" => null,

    // All routes in order
    "routes" => [
        [
            "info" => "403 Forbidden.",
            "internal" => true,
            "path" => "403",
            "handler" => function ($message = null) {
                $html = "Anax 403: Forbidden";
                if ($message && !defined("ANAX_PRODUCTION")) {
                    $html .= "<br>$message";
                }
                return [$html, 403];
            },
        ],
        [
            "info" => "404 Page not found.",
            "internal" => true,
            "path" => "404",
            "handler" => function ($message = null) {
                $html = "Anax 404: Not Found";
                if ($message && !defined("ANAX_PRODUCTION")) {
                    $html .= "<br>$message";
                }
                return [$html, 404];
            },
        ],
        [
            "info" => "500 Internal Server Error.",
            "internal" => true,
            "path" => "500",
            "handler" => function ($message = null) {
                // echo "<pre>";
                // debug_print_backtrace();
                $html = "Anax 500: Internal Server Error";
                if ($message && !defined("ANAX_PRODUCTION")) {
                    $html .= "<br>$message";
                }
                return [$html, 500];
            },
        ],
    ]
];
