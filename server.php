<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require 'vendor/autoload.php';

class CollabServer implements MessageComponentInterface {
    protected $clients;
    protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['type']) && $data['type'] === 'init') {
            $this->users[$from->resourceId] = [
                'conn' => $from,
                'user_id' => $data['user_id'],
                'room_id' => $data['room_id'],
                'user_name' => $data['user_name'] ?? null
            ];
            echo "User {$data['user_id']} joined Room {$data['room_id']}\n";
            return;
        }

        if (!isset($this->users[$from->resourceId])) {
            return;
        }

        $sender = $this->users[$from->resourceId];

        if (isset($data['type']) && $data['type'] === 'typing_in_code') {
            foreach ($this->clients as $client) {
                if ($from === $client) continue;

                $target = $this->users[$client->resourceId] ?? null;
                if ($target && $target['room_id'] == $sender['room_id']) {
                    $client->send(json_encode([
                        'type' => 'user_typing_in_code',
                        'user_name' => $sender['user_name'],
                        'user_id' => $sender['user_id']
                    ]));
                }
            }
            return;
        }

        if (isset($data['target_user_id'])) {
            foreach ($this->users as $resId => $target) {
                if (
                    $target['user_id'] == $data['target_user_id'] &&
                    $target['room_id'] == $sender['room_id']
                ) {
                    $target['conn']->send(json_encode([
                        'type' => $data['type'],
                        'content' => $data['content'],
                        'from' => $sender['user_id'],
                        'unicast' => true
                    ]));
                    echo "Unicast message from User {$sender['user_id']} to User {$target['user_id']} in Room {$sender['room_id']}\n";
                    break;
                }
            }
            return;
        }

        foreach ($this->clients as $client) {
            if ($from === $client) continue;

            $target = $this->users[$client->resourceId] ?? null;
            if ($target && $target['room_id'] == $sender['room_id']) {
                $client->send(json_encode([
                    'type' => $data['type'],
                    'content' => $data['content'],
                    'from' => $sender['user_id'],
                    'unicast' => false
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        echo "Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new CollabServer()
        )
    ),
    8080
);

echo "✅ WebSocket Server running on port 8080...\n";
$server->run();

