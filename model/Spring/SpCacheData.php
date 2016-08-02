<?php
namespace Spring;

/**
 * The DataCache is used to cache data structures, as opposed to 
 * script output. This allows you to cache the creation of large 
 * arrays for example, or the results of slow queries. 
 * This is helpful if your pages are rather dynamic, though some areas aren't.
 * 
 * Usage:
 * 
 * if(!$data = SpCache::get('group', 'unique_id')) {
 * 		$data = "data to be cached";
 * 		SpCache::set('group', 'unique_id', 100, $data);
 * }
 */
class SpCacheData extends SpCache
{
	/**
	 * Get data from cache.
	 * 
	 * @param string group name
	 * @param string unique identifier
	 * @return mixed Data if exists or null
	 */
	public static function get($group, $id)
	{
		if (self::isCached($group, $id)) {
			return unserialize(self::read($group, $id));
		}
		
		return null;
	}
	
	/**
	 * Serializes data and writes it to cache
	 * 
	 * @param string Group name
	 * @param string Unique identifier
	 * @param int Duration of the cache in seconds
	 * @param string Data
	 */
	public static function set($group, $id, $duration, $data)
	{
		self::write($group, $id, $duration, serialize($data));
	}
	
}
?>
