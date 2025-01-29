<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Chat class to handle WebSocket connections
class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $users = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if ($data['type'] === 'join') {
            $this->users[$from->resourceId] = $data['username'];
        }

        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'users' => array_values($this->users),
                'message' => $data['message'] ?? null,
                'from' => $this->users[$from->resourceId] ?? 'Anonymous'
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        unset($this->users[$conn->resourceId]);
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Run WebSocket server
$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new Chat()
        )
    ),
    8080
);

echo "WebSocket server running on ws://localhost:8080\n";
$server->run();
