<?php

namespace Anax\Configure;

/**
 * Trait implementing reading from config file and config directory
 * and storing options in $this->config.
 */
trait Configure2Trait
{
    /**
     * @var [] $config store the configuration in this array.
     */
    protected $config = [];



    /**
     * Read configuration from file or array, if a file, first check in
     * ANAX_APP_PATH/config and then in ANAX_INSTALL_PATH/config.
     *
     * @param array|string $what is an array with key/value config options
     *                           or a file to be included which returns such
     *                           an array.
     *
     * @throws Exception when argument if not a file nor an array.
     *
     * @return self for chaining.
     */
    public function configure($what)
    {
        if (is_array($what)) {
            $this->config = $what;
            return $this;
        }

        $paths = [];
        if (defined("ANAX_APP_PATH")) {
            $paths[] = ANAX_APP_PATH . "/config/$what";
        }

        if (defined("ANAX_INSTALL_PATH")) {
            $paths[] = ANAX_INSTALL_PATH . "/config/$what";
        }
        $paths[] = $what;

        $found = false;
        foreach ($paths as $path) {
            if (is_readable($path)) {
                $found = true;
                $this->config = require $path;
            }

            $parts = pathinfo($path);
            $dir = $parts["dirname"] . "/" . $parts["filename"];
            if (is_dir($dir)) {
                foreach (glob("$dir/*.php") as $file) {
                    $found = true;
                    $config = require $file;
                    if (!is_array($config)) {
                        $config = [$config];
                    }
                    $this->config["items"][basename($file)] = $config;
                }
            }

            if ($found) {
                return $this;
            }
        }

        throw new Exception("Configure item '$what' is not an array nor a readable file.");
    }



    /**
     * Helper function for reading values from the configuration and appy
     * default values where configuration item is missing.
     *
     * @param string $key     matching a key in the config array.
     * @param string $default value returned when config item is not found.
     *
     * @return mixed or null if key does not exists.
     */
    public function getConfig($key, $default = null)
    {
        return isset($this->config[$key])
            ? $this->config[$key]
            : $default;
    }
}
