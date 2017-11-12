<?php

require_once 'Message.php';
require_once 'Listener.php';
require_once 'Heartbeat.php';
require_once 'Node.php';
require_once 'Endpoint.php';

define('NODE_VERSION', 1);
define('DEFAULT_PORT', 12345);

$ips = explode(' ', trim(`hostname -I`));

echo <<<EOF
Vitejte.

Toto je rozhrani komunikacniho uzlu v ramci semestralniho projektu MI-DSV.16.

Predne je potreba zadat IP adresu a port, na kterem bude uzel provozovan.

Napoveda: Vsechny dostupne IP adresy tohoto uzlu:

EOF;

foreach ($ips as $ip)
{
	readline_add_history($ip);
	echo "\t$ip\n";
}

echo "\n";

$nodeIP = readline("IP adresa tohoto uzlu: ");

echo "\nPort pro komunikaci [" . DEFAULT_PORT . "]: ";

readline_clear_history();

$nodePort = readline();
if (empty($nodePort))
{
	$nodePort = "12345";
}

echo "\nDale zadejte IP adresu a port uzlu, ke kteremu se tento uzel pokusi pripojit:\n";

readline_clear_history();

foreach ($ips as $ip)
{
	readline_add_history($ip);
}

$targetIP = readline('Cilova IP adresa: ');

echo "\nCilovy port [" . DEFAULT_PORT . "]: ";

readline_clear_history();

$targetPort = readline();
if (empty($targetPort))
{
	$targetPort = "12345";
}

echo "\nOK, konfigurace dokoncena.\n\tNode: $nodeIP:$nodePort\n\tCil: $targetIP:$targetPort\n ... zahajuji komunikaci ...\n\n";

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