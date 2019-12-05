<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Style chooser.",
            "mount" => "style",
            "handler" => "\Anax\StyleChooser\StyleChooserController",
        ],
    ]
];
