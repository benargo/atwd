<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

class uri {

	private static $params = array();

	/**
	 * setup()
	 *
	 * Explode the URI and set variables to access it by
	 *
	 * @access public
	 * @return void
	 */
	private static function setup()
	{
		// Iterate through the URI
		$uri = explode('&', $_SERVER['REDIRECT_QUERY_STRING']);
		foreach($uri as $segment)
		{
			$segment = explode('=', $segment);
			self::$params[$segment[0]] = $segment[1];
		}
	}

	/**
	 * get()
	 *
	 * @access public
	 * @static true
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name = NULL)
	{
		if(empty(self::$params))
		{
			self::setup();
		}

		if(is_null($name))
		{
			return self::$params;
		}

		return self::$params[$name];
	}

	/**
	 * set()
	 *
	 * @access public
	 * @static true
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public static function set($name, $value)
	{
		self::$params[$name] = $value;
	}

}