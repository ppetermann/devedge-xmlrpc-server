<?php

namespace Devedge\Cache\NoCache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class will always return non-hits
 */
class NoCachePool implements CacheItemPoolInterface
{
    /**
     * @param string $key
     * @return NoCacheItem|\Psr\Cache\CacheItemInterface
     */
    public function getItem($key)
    {
        return new NoCacheItem($key);
    }

    /**
     * @param array $keys
     * @return array|\Traversable
     */
    public function getItems(array $keys = array())
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->getItem($key);
        }
        return $result;
    }

    /**
     * @return $this|CacheItemPoolInterface
     */
    public function clear()
    {
        return $this;
    }

    /**
     * Removes multiple items from the pool.
     *
     * @param array $keys
     * An array of keys that should be removed from the pool.
     * @return static
     * The invoked object.
     */
    public function deleteItems(array $keys)
    {
        return $this;
    }

    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return static
     *   The invoked object.
     */
    public function save(CacheItemInterface $item)
    {
        return $this;
    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     * @return static
     *   The invoked object.
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return $this;
    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *   TRUE if all not-yet-saved items were successfully saved. FALSE otherwise.
     */
    public function commit()
    {
        return true;
    }
}
