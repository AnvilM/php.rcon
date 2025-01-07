## About
This package is a client that supports some implementations of the RCON protocol.
[About RCON](https://developer.valvesoftware.com/wiki/Source_RCON_Protocol)
## Installation

You can get this package using composer

```bash
composer require anvilm/php.rcon
```

## Basic Usage

### Create a client
```php
use AnvilM\RCON\Clients\Minecraft\MinecraftClient;

$Ip = '127.0.0.1'; //Server IP
$Port = 25575; //RCON port

$Client = new MinecraftClient($Ip, $Port);
```

### Authentication
Before sending commands you need to authenticate:
```php
$Password = '123' // RCON Password

$Client->authenticate($Password);
```

### Send commands
To send a command you need to call the sendCommand method:
```php
$Client->sendCommand('time set day');
```

### Available clients:

- **Minecraft**

## Base RCON Client

If you want to create your own client, you can use RconClient to exchange packets with the server.

### RCON Entity
RCON Entity is an object that acts as a DTO and contains data for sending a command and a response from the server, since they use the same structure.

```php
use AnvilM\RCON\Entity\RCON

$data = new RCON(
    1, // packet id, the response will have the same id
    2 // packet type, may vary by implementation
    'time set day' // command 
);
```

### Request method
Request method sends a command to the server and waits for a response, if $timeout is not specified it will wait 5 seconds, if a response has arrived it will return an RCON object with the response data.
```php
use AnvilM\RCON\RCONClient;
use AnvilM\RCON\Entity\RCON;

$client = new RCONClient('127.0.0.1', 25575);

// Minecraft authorization
$data = new RCON(1, 3, '123');

// Returns new RCON(1, 2, '')
$response = $client->request($data);
```

## Connections
This package uses [php.tcp-client](https://github.com/AnvilM/php.tcp-client) so you can manage connections and sockets.

To get the current connection use this method:
```php
use AnvilM\RCON\RCONClient;

$client = new RCONClient('127.0.0.1', 25575);

$connection = $client->getConnection();
```

For example, you can increase or decrease the size of the read and write buffers as needed, this can be useful if the packet size is too large.
```php
// Read buffer
$connection->getSocket()->readBuffer->setBuffer(4096);

// Send buffer
$connection->getSocket()->sendBuffer->setBuffer();
```
