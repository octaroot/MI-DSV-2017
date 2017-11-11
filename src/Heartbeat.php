<?php

class Heartbeat extends Thread
{
	const MAX_DELAY = 11;

	private $lastBeat;

	private $node;

	public function __construct(Node $node)
	{
		$this->updateTimestamp();
		$this->node = $node;
	}

	public function updateTimestamp()
	{
		$this->lastBeat = time();
	}

	public function run()
	{
		while (true)
		{
			sleep(4);
			$this->node->sendBeat();
			sleep(1);
			if (time() > $this->lastBeat + self::MAX_DELAY)
			{
				$this->node->panic();
				$this->updateTimestamp();
			}
		}

	}
}