<?php
/**
 * These routes are for demonstration purpose, to show how routes and
 * handlers can be created.
 */
return [
    // Path where to mount the routes, is added to each route path.
    "mount" => null,

    // All routes in order
    "routes" => [
        [
            "info" => "Just say hi.",
            "method" => null,
            "path" => "",
            "handler" => function () {
                return ["Anax: Just saying Hi!", 200];
            },
        ],
    ]
];
