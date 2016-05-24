<?php
namespace Cache\Cache;

/**
 * Interface for cache drivers.
 */
interface Cache
{
    const STATS_HITS                = 'hits';
    const STATS_MISSES              = 'misses';
    const STATS_UPTIME              = 'uptime';
    const STATS_MEMORY_USAGE        = 'memory_usage';
    const STATS_MEMORY_AVAILABLE    = 'memory_available';

    /**
     * Fetches an entry from the cache.
     *
     * @param $id The id of the cache entry to fetch.
     *
     * @return mixed THe cached data or FALSE, if no cache entry exists for the given id.
     */
    public function fetch($id);

    /**
     * Tests if an entry exists in the cache.
     *
     * @param $id The cache id of the entry to check for.
     *
     * @return bool TRUE if an entry exists for the given cache id, FALSE otherwise.
     */
    public function contains($id);

    /**
     * Puts data into the cache.
     *
     * @param string $id    The cache id.
     * @param mixed $data   The cache entry/data.
     * @param int $lifetime The lifetime in number of seconds for this cache entry
     *                      If zero (the default), the entry never expires (although it may be deleted from the cache
     *                      to make place for other entries).
     * @return bool TRUE if the entry successfully stored in the cache, FALSE otherwise.
     */
    public function save($id, $data, $lifetime = 0);

    /**
     * Delete a cache entry.
     *
     * @param string $id The cache id.
     * @return bool TRUE if the cache entry successfully deleted, FALSE otherwise.
     *              Deleting a non-existing entry is considered successful.
     */
    public function delete($id);

    /**
     * Retrieves cached information from the data store.
     *
     * The server's statistics array has the following value:
     *
     * - <b>hits</b>
     * Number of keys that have been requested and found present.
     *
     * - <b>misses</b>
     * Number of items that have been requested and not found.
     *
     * - <b>uptime</b>
     * Time that the server is running.
     *
     *  - <b>memory_usage</b>
     * Memory used by this server to store items.
     *
     * - <b>memory_available</b>
     * Memory allowed to use for storage.
     *
     * @return array|null An associative array with server's statistics if available, NULL otherwise.
     */
    public function getStats();
}
