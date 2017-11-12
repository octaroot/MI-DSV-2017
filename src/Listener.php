<?php

require_once 'Message.php';
require_once 'Endpoint.php';

class Listener extends Thread
{
	/** @var Node */
	private $node;

	/** @var Heartbeat */
	private $heartbeat;

	/** @var Endpoint */
	private $endpoint;

	public function __construct(Node $node, Heartbeat $heartbeat, Endpoint $endpoint)
	{
		$this->node = $node;
		$this->heartbeat = $heartbeat;
		$this->endpoint = $endpoint;
	}

	public function run()
	{
		if (false === ($sock = stream_socket_server("tcp://" . $this->endpoint->getIP() . ":" . $this->endpoint->getPort(),
				$errno, $errstr)))
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

			case MessageType::PANIC:
				try
				{
					$this->node->forward($msg);
				}
				catch (Exception $e)
				{
					// this node's nextEndpoint is dead
					$this->node->changeNextHop($msg->getData());
					$this->node->callForLeaderElection();
				}

		}
	}
}