<?php

require_once 'Message.php';

class Listener extends Thread
{
	private $node;

	private $heartbeat;

	private $ip, $port;

	public function __construct(Node $node, Heartbeat $heartbeat, $ip, $port)
	{
		$this->node = $node;
		$this->heartbeat = $heartbeat;
		$this->ip = $ip;
		$this->port = $port;
	}

	public function run()
	{
		if (false === ($sock = stream_socket_server("tcp://" . $this->ip . ":" . $this->port, $errno, $errstr)))
		{
			throw new Exception("$errstr ($errno)");
		}

		while (true)
		{
			$conn = stream_socket_accept($sock);

			$buf = fread($conn, 4096);


			if (strlen($buf) > 0)
			{
				echo '<- ' . $buf . "\n";

				$msg = unserialize($buf);

				$this->handleMessage($msg);
			}

			fclose($conn);
		}

		fclose($sock);
	}

	private function handleMessage(Message $msg)
	{
		switch ($msg->getType())
		{
			case MessageType::HEARTBEAT:
				$this->heartbeat->updateTimestamp();
				break;

		}
	}
}