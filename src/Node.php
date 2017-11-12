<?php

require_once 'Message.php';

class Node extends Threaded
{
	/** @var  Endpoint */
	private $endpoint, $nextEndpoint;

	/**
	 * Node constructor.
	 *
	 * @param $endpoint
	 */
	public function __construct($endpoint)
	{
		$this->endpoint = $endpoint;
	}

	public function join($endpoint)
	{
		if (!$this->isNodeAlone())
		{
			$this->quit();
		}

		$this->nextEndpoint = $endpoint;
		$this->connect();

	}

	private function isNodeAlone(): bool
	{
		return !$this->nextEndpoint || ($this->nextEndpoint == $this->endpoint);
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
		if (!$this->nextEndpoint)
		{
			throw new Exception("No other known node!");
		}

		$socket = stream_socket_client("tcp://" . $this->nextEndpoint->getIp() . ":" . $this->nextEndpoint->getPort(),
			$errno, $errstr, 5);

		if (!$socket)
		{
			throw new Exception("Unable to open socket: $errstr ($errno)");
		}

		return $socket;

	}

	public function crash()
	{
		$this->nextEndpoint = $this->endpoint;
	}

	public function sendBeat()
	{
		$msg = new Message();
		$msg->setFrom($this->endpoint);
		$msg->setTo($this->nextEndpoint);

		$this->send($msg);
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