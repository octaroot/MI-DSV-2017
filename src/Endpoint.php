<?php

class Endpoint
{
	private $ip, $port;

	private $is_broadcast;

	public function __construct()
	{
	}

	public static function single($ip, $port)
	{
		$instance = new self();
		$instance->ip = $ip;
		$instance->port = $port;
		$instance->is_broadcast = false;

		return $instance;
	}

	public static function broadcast()
	{
		$instance = new self();
		$instance->ip = null;
		$instance->port = null;
		$instance->is_broadcast = true;

		return $instance;
	}



	public function isBroadcast()
	{
		return $this->is_broadcast;
	}

	public function getIp()
	{
		return $this->ip;
	}

	public function getPort()
	{
		return $this->port;
	}
}