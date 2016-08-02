<?php
namespace Spring;

/**
 * The OutputCache class is used for caching the generated
 * output of your scripts, or certain sections of them.
 *
 * Usage:
 *
 * if (!SpCache::start('group', 'unique_id', 300)) {
 * 		// ... Output
 * 		SpCache::end();
 * }
 *
 */
class SpCacheOutput
{
	/**
	 * Group to categorize caches
	 */
	private static $group;
	/**
	 * Unique identifier
	 */
	private static $id;
	/**
	 * Duration of the cache, in seconds
	 */
	private static $duration;

	/**
	 * Initiates cache and returns the output.
	 * If cached, returns true and the output. If not cached
	 * returns false and starts output buffering.
	 */
	public static function start($group, $id, $duration)
	{
		if (self::isCached($group, $id)) {
			echo self::read($group, $id);
			return true;
		} else {
			ob_start();
			self::$group = $group;
			self::$id = $id;
			self::$duration = $duration;
			return false;
		}
	}

	/**
	 * Ends cache and writes data to disk
	 */
	public static function end()
	{
		$data = ob_get_contents();
		ob_end_flush();

		self::write(self::$group, self::$id, self::$duration, $data);
	}

}
?>
