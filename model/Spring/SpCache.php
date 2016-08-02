<?php
namespace Spring;

/**
 * Manages data and output cache.
 * 
 * Usage:
 * 
 * Output cache
 * 
 * if (!SpCache::start('group', 'unique_id', 300)) {
 * 		// ... Output
 * 		SpCache::end();	
 * }
 * 
 * Data caching
 * 
 * if(!$data = SpCache::get('group', 'unique_id')) {
 * 		$data = "data to be cached";
 * 		SpCache::set('group', 'unique_id', 100, $data);
 * }
 */
abstract class SpCache
{
	/**
	 * Cache enabled or disabled
	 */
	public static $enabled = true;
	/**
	 * Directory to store cache data
	 */
	protected static $store = CACHE_DIR;
	/**
	 * Prefix for cache files
	 */
	protected static $prefix = 'cache_';
	
	/**
	 * Writes data to cache
	 * 
	 * @param string Group name
	 * @param string Unique identifier
	 * @param int Duration of the cache in seconds
	 * @param string Data
	 */
	protected static function write($group, $id, $duration, $data)
	{
		$filename = self::getFilename($group, $id);
		
		if ($fp = fopen($filename, 'xb')) {
			if (flock($fp, LOCK_EX)) {
				fwrite($fp, $data);
			}
			fclose($fp);
			
			// Set file modification time
			touch($filename, time() + $duration);
		}
	}
	
	/**
	 * Read data from cache
	 * 
	 * @param string Group name
	 * @param string Unique identifier
	 * @return string
	 */
	protected static function read($group, $id)
	{
		$filename = self::getFilename($group, $id);
		
		return file_get_contents($filename);
	}
	
	/**
	 * Check if data is cached
	 * @param string Group name
	 * @param string Unique identifier
	 * @return bool
	 */
	protected static function isCached($group, $id)
	{
		$filename = self::getFilename($group, $id);
		
		if (self::$enabled and file_exists($filename) and filemtime($filename) > time()) {
			return true;
		}
		
		@unlink($filename);
		
		return false;
	}
	
	/**
	 * Return cache file name for group name and identifier
	 * @param string Group name
	 * @param string Unique identifier
	 * @return string
	 */
	protected static function getFilename($group, $id)
	{
		$id = md5($id);
		
		return self::$store . self::$prefix . "{$group}_{$id}";
	}
	
	/**
	 * Set new cache file prefix.
	 * The default is cache_ 
	 * @param string New prefix
	 * @return void
	 */
	public static function setPrefix($prefix)
    {
        self::$prefix = $prefix;
    }
    
    /**
     * Set new storage directory.
     * TODO: check that new directory has right permissions
     * @param string absolute path to directory
     */
    public static function setStore($store)
    {
        self::$store = $store;
    }
}
?>
