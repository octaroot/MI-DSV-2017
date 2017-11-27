#!/usr/local/bin/php
<?php

//error_reporting(0);

require_once 'Logger.php';
require_once 'ChatMessage.php';
require_once 'Chat.php';
require_once 'Output.php';
require_once 'Message.php';
require_once 'MessageType.php';
require_once 'Listener.php';
require_once 'Heartbeat.php';
require_once 'Node.php';
require_once 'Endpoint.php';

define('NODE_VERSION', 1);
define('DEFAULT_PORT', 12345);

$ips = explode(' ', trim(`hostname -I`));

echo "Possible IPs: " . implode(', ', $ips) . "\n";

foreach ($ips as $ip)
{
	readline_add_history($ip);
}

$nodeIP = readline("Listen IP (e.g. 127.0.0.1):");

readline_clear_history();

$nodePort = readline("Listen port [" . DEFAULT_PORT . "]: ");
if (empty($nodePort))
{
	$nodePort = DEFAULT_PORT;
}
echo "\n";

readline_clear_history();

foreach ($ips as $ip)
{
	readline_add_history($ip);
}

$targetIP = readline('Connect to IP: ');

readline_clear_history();

$targetPort = readline("\nConnect to port [" . DEFAULT_PORT . "]: ");
if (empty($targetPort))
{
	$targetPort = DEFAULT_PORT;
}

Output::log("Connection to $targetIP:$targetPort");
Logger::log("\nOK, node: $nodeIP:$nodePort connecting to $targetIP:$targetPort\n\n");

$endpoint = Endpoint::single($nodeIP, $nodePort);
$targetEndpoint = Endpoint::single($targetIP, $targetPort);

$node = new Node($endpoint);
$heartbeat = new Heartbeat($node);
$listener = new Listener($node, $heartbeat, $endpoint);

$listener->start();
$heartbeat->start();

$node->connect();

while (!$listener->isStarted())
{
	sleep(1);
}

if ($endpoint != $targetEndpoint)
{
	$node->askToJoin($targetEndpoint);
}
else
{
	Output::log("You are the only node in the network.");
	Output::log("Connected. You can start chatting now.\n\n");
}

$chat = new Chat($node);

while ($chat->isConnected())
{
	$chat->sendMessage(readline());
}

$listener->done = true;