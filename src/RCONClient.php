<?php

namespace AnvilM\RCON;

use Anvil\TCPClient\Client;
use Anvil\TCPClient\Core\Connection;
use Anvil\TCPClient\Core\ConnectionException;
use Anvil\TCPClient\Core\Socket\SocketException;
use AnvilM\RCON\Entity\RCON;

class RCONClient
{
    /** @var Connection $connection */
    private $connection;

    /**
     * @throws RCONException
     */
    public function __construct($host, $port)
    {
        try {
            $this->connection = Client::connect($host, $port);

            $this->connection->getSocket()->readBuffer->setBuffer(16384);
        } catch (SocketException $e) {
            throw new RCONException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws RCONException
     */
    private function sendCommand(RCON $data, int $timeout = 5): self
    {
        $packetLength = 10 + strlen($data->getBody());

        $packet = pack('V', $packetLength)
            . pack('V', $data->getId())
            . pack('V', $data->getType())
            . $data->getBody()
            . "\x00\x00";

        try {
            $this->connection->send($packet, $timeout);
        } catch (ConnectionException $e){
            throw new RCONException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }


    /**
     * @throws RCONException
     */
    private function getResponse(int $timeout = 5): RCON
    {
        try {
            $responseData = unpack('V1length/V1id/V1type', $this->connection->readBinary(12, $timeout));
            $packets = intdiv($responseData['length'], 2048) + 1;
            $body = '';
            while ($packets !== 0) {
                $response = $this->connection->readBinary(2048, $timeout);
                $body .= $response;
                $packets--;
            }
            $body = unpack('a*body', $body)['body'];
        } catch (ConnectionException $e){
            throw new RCONException($e->getMessage(), $e->getCode(), $e);
        }

        return new RCON(
            $responseData['id'],
            $responseData['type'],
            $body
        );
    }

    /**
     * @throws RCONException
     */
    public function request(RCON $data, int $timeout = 5): RCON
    {
        return $this->sendCommand($data, $timeout)->getResponse($timeout);
    }

    /**
     * @throws RCONException
     */
    public function disconnect(): void
    {
        try {
            $this->connection->close();
        } catch (SocketException $e) {
            throw new RCONException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws RCONException
     */
    public function connect()
    {
        try {
            $this->connection->open();
        } catch (SocketException $e) {
            throw new RCONException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
