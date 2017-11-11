<?php

require_once 'Message.php';

class Node extends Threaded
{
	/** Node IP:port */
	private $ip, $port;

	/** Node->next IP:port */
	private $next_ip, $next_port;

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
	}

	public function join($ip, $port)
	{
		if (!$this->isNodeAlone())
		{
			$this->quit();
		}

		$this->next_ip = $ip;
		$this->next_port = $port;
		$this->connect();

	}

	private function isNodeAlone(): bool
	{
		return !$this->next_ip || ($this->ip == $this->next_ip && $this->port == $this->next_port);
	}

	public function quit()
	{
		if ($this->isNodeAlone())
		{
			return;
		}
		//TODO
	}

	public function connect()
	{
		if (!$this->next_ip || !$this->next_port)
		{
			throw new Exception("No other known node!");
		}

		$socket = stream_socket_client("tcp://" . $this->next_ip . ":" . $this->next_port, $errno, $errstr, 5);

		if (!$socket)
		{
			throw new Exception("Unable to open socket: $errstr ($errno)");
		}

		return $socket;

	}

	public function crash()
	{
		$this->next_ip = $this->ip;
		$this->next_port = $this->port;
	}

	public function sendBeat()
	{
		$this->send(new Message(MessageType::HEARTBEAT));
	}

	private function send(Message $message)
	{
		$data = serialize($message);
		echo "-> " . $data . "\n";

		$socket = $this->connect();
		if (fwrite($socket, $data) === false)
		{
			throw new Exception("Unable to write to socket");
		}
		fclose($socket);
	}

	public function panic()
	{

	}

}