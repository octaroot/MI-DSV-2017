<?php

require_once 'Message.php';

class Node extends Threaded
{
	/** @var  Endpoint */
	private $endpoint, $nextEndpoint, $leaderEndpoint;

	private $electionParticipant;

	/**
	 * Node constructor.
	 *
	 * @param $endpoint
	 */
	public function __construct($endpoint)
	{
		$this->endpoint = $this->nextEndpoint = $this->leaderEndpoint = $endpoint;
		$this->electionParticipant = false;
	}

	public function join($endpoint)
	{
		if (!$this->isNodeAlone())
		{
			$this->quit();
		}

		$this->electionParticipant = false;
		$this->nextEndpoint = $endpoint;
		$this->leaderEndpoint = null;
		$this->connect();

	}

	private function isNodeAlone(): bool
	{
		return !$this->nextEndpoint || ($this->nextEndpoint == $this->endpoint);
	}

	public function quit()
	{
		$this->electionParticipant = false;
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

		return $this->connectTo($this->nextEndpoint);
	}

	private function connectTo(Endpoint $endpoint)
	{
		$socket = stream_socket_client("tcp://" . $endpoint, $errno, $errstr, 2);

		if (!$socket)
		{
			throw new Exception("Unable to open socket: $errstr ($errno)");
		}

		return $socket;

	}

	public function acknowledgeLeaderNotice(Message $msg)
	{
		$this->electionParticipant = false;
		$this->leaderEndpoint = $msg->getData();

		if ($msg->getFrom() != $this->endpoint)
		{
			$this->forward($msg);

		}
	}

	public function forward(Message $message)
	{
		$this->send($message);
	}

	private function sendTo(Endpoint $endpoint, Message $message)
	{
		$data = serialize($message);
		echo "-> " . $message . "\n";

		$socket = $this->connectTo($endpoint);
		if (fwrite($socket, $data) === false)
		{
			throw new Exception("Unable to write to socket");
		}
		fclose($socket);
	}

	private function send(Message $message)
	{
		return $this->sendTo($this->nextEndpoint, $message);
	}

	public function askToJoin(Endpoint $target)
	{
		$msg = new Message();
		$msg->setType(MessageType::JOIN_REQUEST);
		$msg->setFrom($this->endpoint);
		$msg->setTo($target);

		$this->sendTo($target, $msg);
	}

	public function acceptNewNode(Message $msg)
	{

		$reply = new Message();
		$reply->setType(MessageType::JOIN_REPLY);
		$reply->setTo($msg->getFrom());
		$reply->setFrom($this->endpoint);
		$reply->setData($this->nextEndpoint);

		$this->changeNextHop($msg->getFrom());

		$this->send($reply);
		$this->callForLeaderElection();
	}

	public function changeNextHop(Endpoint $next)
	{
		ECHO "CHANGIN NEXT TO $next \n";
		$this->nextEndpoint = $next;
		$this->connect();
	}

	public function callForLeaderElection()
	{
		$this->electionParticipant = true;
		$this->leaderEndpoint = null;

		$msg = new Message();
		$msg->setTo(Endpoint::broadcast());
		$msg->setType(MessageType::ELECTION);
		$msg->setData($this->endpoint);

		$this->send($msg);
	}

	public function participateInLeaderElection(Message $msg)
	{
		$this->leaderEndpoint = null;

		$myUID = $this->endpoint->getUID();
		$maxUID = $msg->getData()->getUID();

		if ($myUID < $maxUID)
		{
			$this->forward($msg);
			$this->electionParticipant = true;
		}
		elseif ($myUID == $maxUID)
		{
			$this->electionParticipant = false;

			$msg = new Message();
			$msg->setFrom($this->endpoint);
			$msg->setType(MessageType::ELECTED_NOTICE);
			$msg->setTo(Endpoint::broadcast());
			$msg->setData($this->endpoint);

			$this->send($msg);

		}
		elseif (!$this->electionParticipant)
		{
			$msg->setData($this->endpoint);
			$this->forward($msg);
		}
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
		$msg->setType(MessageType::HEARTBEAT);

		try
		{
			$this->send($msg);
		}
		catch (Exception $e)
		{
			echo "FAILED TO SEND HEATBEAT\n";
		}
	}

	public function panic()
	{
		$msg = new Message();
		$msg->setFrom($this->endpoint);
		$msg->setData($this->endpoint);
		$msg->setTo($this->nextEndpoint);
		$msg->setType(MessageType::PANIC);

		$this->send($msg);
	}

	public function handleData(Message $msg)
	{
		//TODO
	}

}