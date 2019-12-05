<?php
/**
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "session" => [
            "shared" => false,
            "callback" => function () {
                $object = new \stdClass();
                return $object;
            }
        ],
    ],
];
