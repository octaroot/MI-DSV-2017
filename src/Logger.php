<?php

define('LOG_FILE', '/root/connection.log');

final class Logger extends Threaded
{
	public static function log($string)
	{
		file_put_contents(LOG_FILE, $string . "\n", FILE_APPEND);
	}
}