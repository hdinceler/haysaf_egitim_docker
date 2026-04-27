<?php
set_time_limit(0);

$host = '0.0.0.0';
$port = 12;

// TCP socket oluştur
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

$clients = [$socket];

echo "WS Server started on $host:$port\n";

while (true) {
    $changed = $clients;
    socket_select($changed, $null, $null, 0, 10);

    foreach ($changed as $sock) {

        // Yeni bağlantı
        if ($sock === $socket) {
            $client = socket_accept($socket);
            $clients[] = $client;

            $header = socket_read($client, 1024);
            handshake($client, $header);

            echo "Client connected\n";
            continue;
        }

        // Mesaj oku
        $data = @socket_read($sock, 1024, PHP_BINARY_READ);

        if ($data === false || strlen($data) === 0) {
            unset($clients[array_search($sock, $clients)]);
            socket_close($sock);
            echo "Client disconnected\n";
            continue;
        }

        $msg = decode($data);
        echo "Received: $msg\n";

        // Echo back
        $response = encode("Sunucu aldı: $msg");
        foreach ($clients as $send) {
            if ($send !== $socket) {
                @socket_write($send, $response, strlen($response));
            }
        }
    }
}

// --- Yardımcılar ---

function handshake($client, $headers)
{
    preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $matches);
    $key = trim($matches[1]);

    $accept = base64_encode(
        sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)
    );

    $upgrade =
        "HTTP/1.1 101 Switching Protocols\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "Sec-WebSocket-Accept: $accept\r\n\r\n";

    socket_write($client, $upgrade, strlen($upgrade));
}

function encode($text)
{
    $len = strlen($text);
    return chr(129) . chr($len) . $text;
}

function decode($data)
{
    $len = ord($data[1]) & 127;
    $mask = substr($data, 2, 4);
    $text = substr($data, 6);

    $out = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $out .= $text[$i] ^ $mask[$i % 4];
    }
    return $out;
}