<?php

namespace Anax\Configure;

/**
 * Interface for classes needing access to configuration files.
 */
interface ConfigureInterface
{
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
    public function configure($what);
}
