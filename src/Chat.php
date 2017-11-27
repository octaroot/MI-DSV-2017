<?php

final class Chat extends Threaded
{
	/** @var Node */
	private $node;

	private $name;

	/**
	 * Chat constructor.
	 *
	 * @param Node $node
	 */
	public function __construct(Node $node)
	{
		$this->node = $node;
		$this->name = $node->getNodeEndpoint();
	}

	public function sendMessage($message)
	{
		$message = trim($message);
		if (!strlen($message))
		{
			return;
		}

		if ($message[0] == '!')
		{
			return $this->handleCommand(substr($message, 1));
		}

		return $this->node->sendChatMessage(new ChatMessage($this->name, $message));
	}

	private function handleCommand($command)
	{
		$command = explode(' ', $command);
		switch ($command[0])
		{
			case 'leader':
				Output::log("The leader is: " . $this->node->getLeaderEndpoint());
				break;

			case 'name':
				if (count($command) == 2)
				{
					$this->name = $command[1];
				}

				Output::log('Your name is: ' . $this->name);
				break;

			case 'quit':
				$this->node->quit();

				//sleep 0.5s
				usleep(500000);

				posix_kill(posix_getpid(), 15);
				break;
		}
	}

}