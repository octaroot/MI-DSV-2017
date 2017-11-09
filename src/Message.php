<?php

class Message
{

}

final class MessageType
{
	const JOIN_REQUEST = 0x0;
	const QUIT_NOTICE = 0x1;
	const ELECTION = 0x02;
	const ELECTED_NOTICE = 0x03;
	const DATA = 0x04;
}