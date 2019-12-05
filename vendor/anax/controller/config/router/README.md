How to add a controller to the router
=====================================

This is a sample configuration file on how to add controller classes to several mount points.

```
/**
 * Routes to ease testing.
 */
return [
    // Path where to mount the routes, is added to each route path.
    "mount" => "sample",

    // All routes in order
    "routes" => [
        [
            "info" => "Sample controller app style.",
            "mount" => "app",
            "handler" => "\Anax\Controller\SampleAppController",
        ],
        [
            "info" => "Sample controller di style.",
            "mount" => "di",
            "handler" => "\Anax\Controller\SampleController",
        ],
        [
            "info" => "Sample controller di style with json responses.",
            "mount" => "json",
            "handler" => "\Anax\Controller\SampleJsonController",
        ],
    ]
];
```

The ErrorHandlerController is mounted as internal routes, these are not exposed to the outside world and only internally used within the router.

```
/**
 * Internal routes that deal with error situations when pages are not found,
 * forbidden or internal error happens.
 */
return [
    "routes" => [
        [
            "info" => "Internal routes for error handling.",
            "internal" => true,
            "handler" => "\Anax\Controller\ErrorHandlerController",
        ],
    ]
];
```
