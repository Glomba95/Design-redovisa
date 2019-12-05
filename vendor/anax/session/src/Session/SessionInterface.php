<?php

namespace Anax\Session;

/**
 * Interface for classes wrapping sessions.
 */
interface SessionInterface
{
    /**
     * Set a session name.
     *
     * @param string $name to set as session name.
     *
     * @return self
     */
    public function name($name);



    /**
     * Start the session (ignore when on cli).
     *
     * @return self
     */
    public function start();



    /**
     * Check if a value is set in the session.
     *
     * @param string $key   in session variable.
     *
     * @return boolean true if $key is set, else false.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function has($key);



    /**
     * Get a value from the session.
     *
     * @param string $key     in session variable.
     * @param mixed  $default default value to return when key is not set
     *                        in the session.
     *
     * @return mixed value from session and null if not set.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function get($key, $default = null);



    /**
     * Read a value from the session and unset it, this is a form of read
     * once flash memory using the session.
     *
     * @param string $key     in session variable.
     * @param mixed  $default default value to return when key is not set
     *                        in the session.
     *
     * @return mixed value from session and null if not set.
     */
    public function getOnce($key, $default = null);



    /**
     * Set values in session.
     *
     * @param string $key   in session variable.
     * @param mixed  $value to set in session.
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function set($key, $value);



    /**
     * Unset session value of this key.
     *
     * @param string $key in session variable.
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function delete($key);



    /**
     * Destroy the session.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function destroy();
}
