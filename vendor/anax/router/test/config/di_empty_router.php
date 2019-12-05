<?php
/**
 * Sample service for an empty router without any configured services.
 */
return [
    "services" => [
        "router" => [
            "shared" => true,
            "callback" => function () {
                $router = new \Anax\Route\Router();
                $router->setDI($this);

                // Load the configuration files
                // $cfg = $this->get("configuration");
                // $config = $cfg->load("router");

                // Set DEVELOPMENT/PRODUCTION mode, if defined
                $mode = $config["config"]["mode"] ?? null;
                if (isset($mode)) {
                    $router->setMode($mode);
                } elseif (defined("ANAX_PRODUCTION")) {
                    $router->setMode(\Anax\Route\Router::PRODUCTION);
                }

                // // Add routes from configuration file
                // $file = null;
                // try {
                //     $file = $config["file"];
                //     $router->addRoutes($config["config"] ?? []);
                //     foreach ($config["items"] as $routes) {
                //         $file = $routes["file"];
                //         $router->addRoutes($routes["config"]);
                //     }
                // } catch (Exception $e) {
                //     throw new Exception(
                //         $e->getMessage()
                //         . " Configuration file: '$file'"
                //     );
                // }

                return $router;
            }
        ],
    ],
];
