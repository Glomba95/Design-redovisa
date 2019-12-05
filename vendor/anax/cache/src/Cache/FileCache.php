<?php

namespace Anax\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * File based cache in line with PSR-3.
 */
class FileCache implements CacheInterface
{
    /**
     * @var string $cachePath  the path to the cache dir.
     * @var int    $timeToLive default setting for time to live in seconds.
     */
    private $cachePath;
    private $timeToLive = 7 * 24 * 60 * 60;



    /**
     * Set the base for the cache path where all items are stored.
     *
     * @param string $path A valid writable path.
     *
     * @return void.
     *
     * @throws \Psr\SimpleCache\CacheException when the path is not writable.
     */
    public function setPath(string $path) : void
    {
        if (!is_writable($path)) {
            throw new Exception("The path to the cache is not writable.");
        }

        $this->cachePath = $path;
    }



    /**
     * Set default setting for time to live for a cache item.
     *
     * @param int $timeToLive in seconds.
     *
     * @return void.
     *
     * @throws \Psr\SimpleCache\CacheException when the path is not writable.
     */
    public function setTimeToLive(int $timeToLive) : void
    {
        $this->timeToLive = $timeToLive;
    }



    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case
     *               of cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null)
    {
        $file = $this->filename($key);

        if (is_file($file)) {
            // if ($age) {
            //     $age = filemtime($file) + $this->timeToLive > time();
            // }
            // 
            // if (!$age) {
            //     // json
            //     // text
            //     return unserialize(file_get_contents($file));
            // }

            return unserialize(file_get_contents($file));
        }

        return $default;
    }



    /**
     * Persists data in the cache, uniquely referenced by a key with an
     * optional expiration TTL time.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store,
     *                                     must be serializable.
     * @param null|int|\DateInterval $ttl  Optional. The TTL value of this
     *                                     item. If no value is sent and
     *                                     the driver supports TTL then the
     *                                     library may set a default value
     *                                     for it or let the driver take care
     *                                     of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null)
    {
        $file = $this->filename($key);

        // json
        // text
        if (!file_put_contents($file, serialize($value))) {
            throw new Exception("Failed writing cache object '$key'.");
        }
    }



    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key)
    {
        @unlink($this->filename($key));
    }



    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        $files = glob($this->cachePath . "/*");
        $items = count($files);
        array_map('unlink', $files);
        return true;
    }



    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single
     *                          operation.
     * @param mixed    $default Default value to return for keys that do not
     *                          exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not
     * exist or are stale will have $default as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null)
    {

    }



    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a
     *                                       multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this
     *                                       item. If no value is sent and
     *                                       the driver supports TTL then the
     *                                       library may set a default value
     *                                       for it or let the driver take care
     *                                       of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null)
    {

    }



    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there
     * was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys)
    {

    }



    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming
     * type purposes and not to be used within your live applications
     * operations for get/set, as this method is subject to a race condition
     * where your has() will return true and immediately after, another script
     * can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has($key)
    {

    }



    /**
     * Create a key to use for the cache.
     *
     * @param string $class name of the class, including
     *                      namespace.
     * @param string $id    unique id for item in each class.
     *
     * @return string the filename.
     */
    public function createKey($class, $id)
    {
        return str_replace('\\', '-', $class) . '#' . $id;
    }



    /**
     * Generate a filename for the cached object.
     *
     * @param string $key to the cached object.
     *
     * @return string the filename.
     */
    private function filename($key)
    {
        return $this->cachePath . "/" . $key;
    }



    // /**
    //  * Get an item from the cache if available.
    //  *
    //  * @param string  $key to the cached object.
    //  * @param boolean $age check the age or not, defaults to
    //  *                     false.
    //  *
    //  * @return mixed the cached object or false if it has aged
    //  *               or null if it does not exists.
    //  */
    // public function get($key, $age = false)
    // {
    //     $file = $this->filename($key);
    // 
    //     if (is_file($file)) {
    //         if ($age) {
    //             $age = filemtime($file) + $this->config['age'] > time();
    //         }
    // 
    //         if (!$age) {
    //             // json
    //             // text
    //             return unserialize(file_get_contents($file));
    //         }
    //         return false;
    //     }
    //     return null;
    // }



    // /**
    //  * Put an item to the cache.
    //  *
    //  * @param string $key  to the cached object.
    //  * @param mixed  $item the object to be cached.
    //  *
    //  * @throws Exception if failing to write to cache.
    //  *
    //  * @return void
    //  */
    // public function put($key, $item)
    // {
    //     $file = $this->filename($key);
    // 
    //     // json
    //     // text
    //     if (!file_put_contents($file, serialize($item))) {
    //         throw new \Exception(
    //             t("Failed writing cache object '!key'.", [
    //                 '!key' => $key
    //             ])
    //         );
    //     }
    // }



    // /**
    //  * Prune a item from cache.
    //  *
    //  * @param string $key to the cached object.
    //  *
    //  * @return void
    //  */
    // public function prune($key)
    // {
    //     @unlink($this->filename($key));
    // }



    // /**
    //  * Prune all items from cache.
    //  *
    //  * @return int number of items removed.
    //  */
    // public function pruneAll()
    // {
    //     $files = glob($this->config['basepath'] . '/*');
    //     $items = count($files);
    //     array_map('unlink', $files);
    //     return $items;
    // }
}
