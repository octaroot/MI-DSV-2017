<?php

final class ChatMessage
{
	/** @var  string */
	private $name;

	/** @var  DateTime */
	private $date;

	/** @var  string */
	private $message;

	/**
	 * ChatMessage constructor.
	 *
	 * @param string   $name
	 * @param string   $message
	 */
	public function __construct($name, $message)
	{
		$this->name = $name;
		$this->date = null;
		$this->message = $message;
	}

	public function updateDateTime()
	{
		$this->date = new DateTime();
	}

	public function __toString()
	{
		$name = $this->name ?? 'unknown';
		$date = ($this->date ?? new DateTime('now'))->format('H:i');
		$message = $this->message ?? '(no message)';

		return "$name ($date): $message";
	}
}