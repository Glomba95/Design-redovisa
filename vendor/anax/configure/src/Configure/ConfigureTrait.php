<?php

namespace Anax\Configure;

/**
 * Trait implementing reading from config-file and storing options in
 * $this->config.
 */
trait ConfigureTrait
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

        if (defined("ANAX_APP_PATH")) {
            $path = ANAX_APP_PATH . "/config/$what";
            if (is_readable($path)) {
                $this->config = require $path;
                return $this;
            }
        }

        if (defined("ANAX_INSTALL_PATH")) {
            $path = ANAX_INSTALL_PATH . "/config/$what";
            if (is_readable($path)) {
                $this->config = require $path;
                return $this;
            }
        }

        if (is_readable($what)) {
            $this->config = require $what;
            return $this;
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
