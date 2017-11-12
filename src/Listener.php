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
				break;

			case MessageType::ELECTION:
				$this->node->participateInLeaderElection($msg);
				break;

			case MessageType::ELECTED_NOTICE:
				$this->node->acknowledgeLeaderNotice($msg);
				break;

			case MessageType::JOIN_REPLY:
				$this->node->join($msg->getData());
				break;

			case MessageType::JOIN_REQUEST:
				$this->node->acceptNewNode($msg);
				break;

			case MessageType::DATA:
				if ($this->endpoint != $msg->getTo())
				{
					$this->node->forward($msg);
				}
				else
				{
					$this->node->handleData($msg);
				}
				break;



		}
	}
}