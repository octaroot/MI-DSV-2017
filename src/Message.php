<?php

class Message
{
	/** @var  int */
	private $type;

	/** @var Endpoint */
	private $from, $to;

	/** @var mixed */
	private $data;

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType(int $type)
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
	const PANIC = 0x06;

}