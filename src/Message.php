<?php

require_once 'MessageType.php';

class Message
{
	/** @var  string */
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
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type)
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

	public function __toString()
	{
		$output = [];
		if ($this->type)
		{
			$output[] = "[" . $this->type . "]";
		}
		if ($this->from)
		{
			$output[] = 'From: ' . $this->from;
		}
		if ($this->to)
		{
			$output[] = 'To: ' . $this->to;
		}
		if ($this->data)
		{
			$output[] = $this->data;
		}

		return implode(', ', $output);
	}
}