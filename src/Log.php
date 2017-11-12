<?php

define('LOG_FILE', '/root/chat.log');

final class Log
{
	private $handle;

	private function __construct()
	{
		$this->handle = fopen(LOG_FILE, 'w');
	}

	public static function getInstance()
	{
		static $inst = null;
		if ($inst === null)
		{
			$inst = new Log();
		}

		return $inst;
	}

	public function log($string)
	{
		return fwrite($this->handle, $string . "\n");
	}

	public function __destruct()
	{
		fclose($this->handle);
	}
}