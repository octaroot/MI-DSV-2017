<?php

class Message
{
	/** @var  int */
	private $type;

	/** @var Endpoint */
	private $from, $to;

	/**
	 * Message constructor.
	 *
	 * @param int $type
	 */
	public function __construct(int $type)
	{
		$this->type = $type;
	}

	/**
	 * @return Endpoint
	 */
	public function getFrom(): Endpoint
	{
		return $this->from;
	}

	/**
	 * @param Endpoint $from
	 */
	public function setFrom(Endpoint $from)
	{
		$this->from = $from;
	}

	/**
	 * @return Endpoint
	 */
	public function getTo(): Endpoint
	{
		return $this->to;
	}

	/**
	 * @param Endpoint $to
	 */
	public function setTo(Endpoint $to)
	{
		$this->to = $to;
	}


}

final class MessageType
{
	const JOIN_REQUEST = 0x0;
	const QUIT_NOTICE = 0x1;
	const ELECTION = 0x02;
	const ELECTED_NOTICE = 0x03;
	const DATA = 0x04;
	const HEARTBEAT = 0x05;
}