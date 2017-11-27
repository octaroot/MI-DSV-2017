<?php

define('CHAT_LOG_FILE', '/root/chat.log');

final class Output extends Threaded
{
	public static function log($string)
	{
		file_put_contents(CHAT_LOG_FILE, $string . "\n", FILE_APPEND);
	}
}