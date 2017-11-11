<?php

class Message
{
	/** @var  int */
	private $type;

	/**
	 * Message constructor.
	 *
	 * @param int $type
	 */
	public function __construct(int $type)
	{
		$this->type = $type;
	}

	public function getType(): int
	{
		return $this->type;
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