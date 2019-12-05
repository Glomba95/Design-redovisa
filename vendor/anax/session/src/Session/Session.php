<?php

namespace Anax\Session;

/**
 * Class for wrapping sessions.
 */
class Session implements SessionInterface
{
    /**
     * Set a session name.
     *
     * @param string $name to set as session name.
     *
     * @return self
     */
    public function name($name)
    {
        if (php_sapi_name() !== 'cli') {
            session_name($name);
        }
        return $this;
    }



    /**
     * Start the session (ignore when on cli).
     *
     * @return self
     */
    public function start()
    {
        if (php_sapi_name() !== 'cli') {
            session_start();
        }
        return $this;
    }



    /**
     * Check if a value is set in the session.
     *
     * @param string $key   in session variable.
     *
     * @return boolean true if $key is set, else false.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function has($key)
    {
        return isset($_SESSION) && isset($_SESSION[$key]);
    }



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
    public function get($key, $default = null)
    {
        return $this->has($key)
            ? $_SESSION[$key]
            : $default;
    }



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
    public function getOnce($key, $default = null)
    {
        $read = $this->get($key, $default);
        $this->delete($key);
        return $read;
    }



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
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }



    /**
     * Unset session value of this key.
     *
     * @param string $key in session variable.
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        return $this;
    }



    /**
     * Destroy the session (works even on cli).
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function destroy()
    {
        // Unset all of the session variables.
        $_SESSION = [];

        // Delete the session cookie.
        if (php_sapi_name() !== 'cli'
            && ini_get("session.use_cookies")
        ) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        if (php_sapi_name() !== 'cli') {
            session_destroy();
        }
    }



    /**
     * To enable var_dump() of the session object.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __debugInfo() : ?array
    {
        return $_SESSION ?? null;
    }
}
