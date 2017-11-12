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

	public static function uid($uid)
	{
		$instance = new self();
		$instance->port = $uid & 0xFFFF;
		$instance->ip = long2ip($uid >> 16);
		$instance->is_broadcast = false;

		return $instance;
	}

	public function getUID()
	{
		if ($this->isBroadcast())
		{
			throw new Exception("Broadcast is not a single endpoint, cannot get UID");
		}

		return (ip2long($this->ip) << 16) + $this->port;
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

	public function __toString()
	{
		if ($this->isBroadcast())
		{
			return "Broadcast";
		}
		else
		{
			return $this->ip . ':' . $this->port;
		}
	}
}