<?php
/**
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "request" => [
            "shared" => false,
            "callback" => function () {
                $object = new \stdClass();
                return $object;
            }
        ],
        "response" => [
            "shared" => true,
            "callback" => "\stdClass",
        ],
    ],
];
