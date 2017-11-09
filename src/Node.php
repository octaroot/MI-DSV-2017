<?php

require_once 'Message.php';

class Node
{
	/** Node IP:port */
	private $ip, $port;

	/** Node->next IP:port */
	private $next_ip, $next_port;

	private $socket = null;

	/**
	 * Node constructor.
	 *
	 * @param $ip
	 * @param $port
	 */
	public function __construct($ip, $port)
	{
		$this->ip = $ip;
		$this->port = $port;
		$this->next_ip = $ip;
		$this->next_port = $port;
	}

	public function __destruct()
	{
		if ($this->socket)
		{
			fclose($this->socket);
		}
	}

	public function join($ip, $port)
	{
		if (!$this->isNodeAlone())
		{
			$this->quit();
		}

		$this->socket = fsockopen($ip, $port, $errno, $errstr, 5);

		if (!$this->socket)
		{
			throw new Exception("Unable to open socket: " . $errstr, $errno);
		}

	}

	private function isNodeAlone(): bool
	{
		return $this->ip == $this->next_ip && $this->port == $this->next_port;
	}

	public function quit()
	{
		if ($this->isNodeAlone())
		{
			return;
		}

		//TODO
	}

	public function crash()
	{
		$this->next_ip = $this->ip;
		$this->next_port = $this->port;
		if ($this->socket)
		{
			fclose($this->socket);
			$this->socket = null;
		}
	}

}