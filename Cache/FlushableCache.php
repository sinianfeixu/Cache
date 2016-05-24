<?php
namespace Cache\Cache;

/**
 * Interface for cache that can be flushed.
 */
interface FlushableCache
{
    /**
     * Flushes all cache entries, globally.
     *
     * @return bool TRUE if the cache entries were successfully flushed, FALSE otherwise.
     */
    public function flushAll();
}