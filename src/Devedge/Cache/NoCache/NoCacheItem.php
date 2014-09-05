<?php

namespace Devedge\Cache\NoCache;

use Psr\Cache\CacheItemInterface;

/**
 * Class NoCacheItem, a cache that doesn't.
 *
 */
class NoCacheItem implements CacheItemInterface
{

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * always null since we don't cache
     * @return \Serializable|mixed
     */
    public function get()
    {
        return null;
    }

    /**
     * as if..
     *
     * @param \Serializable $value
     * @param int $ttl
     * @returns boolean
     */
    public function set($value = null, $ttl = null)
    {
        return true;
    }

    /**
     * we never hit the cache since we dont cache
     * @return bool
     */
    public function isHit()
    {
        return false;
    }

    /**
     * Removes the current key from the cache.
     *
     * @return \Psr\Cache\CacheItemInterface
     *   The current item.
     */
    public function delete()
    {
        return $this;
    }

    /**
     * nope doesnt exist. ever
     * @return bool
     */
    public function exists()
    {
        return false;
    }

    /**
     * This method is used to tell future calls to this item if re-regeneration of
     * this item's data is in progress or not.
     *
     * This can be used to prevent the dogpile effect to stop lots of requests re-generating
     * the fresh data over and over.
     *
     * @return boolean
     */
    public function isRegenerating()
    {
        return false;
    }

    /**
     * Sets the expiration for this cache item.
     *
     * @param int|\DateTime $ttl
     *   - If an integer is passed, it is interpreted as the number of seconds
     *     after which the item MUST be considered expired.
     *   - If a DateTime object is passed, it is interpreted as the point in
     *     time after which the item MUST be considered expired.
     *   - If null is passed, a default value MAY be used. If none is set,
     *     the value should be stored permanently or for as long as the
     *     implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function setExpiration($ttl = null)
    {
        return $this;
    }

    /**
     * Returns the expiration time of a not-yet-expired cache item.
     *
     * If this cache item is a Cache Miss, this method MAY return the time at
     * which the item expired or the current time if that is not available.
     *
     * @return \DateTime
     *   The timestamp at which this cache item will expire.
     */
    public function getExpiration()
    {
        // expire now
        return new \DateTime();
    }
}
