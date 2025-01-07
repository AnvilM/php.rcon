<?php

declare(strict_types=1);

namespace AnvilM\RCON\Clients\Minecraft;

use AnvilM\RCON\Clients\Minecraft\Exceptions\MinecraftClientException;
use AnvilM\RCON\Entity\RCON;
use AnvilM\RCON\RCONClient;
use AnvilM\RCON\RCONException;

final class MinecraftClient
{
    /** @var RCONClient */
    private $RCONClient;

    /**
     * @throws RCONException
     */
    public function __construct(string $host, int $port)
    {
        $this->RCONClient = new RCONClient($host, $port);
    }

    /**
     * @throws RCONException
     */
    public function authenticate(string $password, int $id = 0): RCON
    {
        $response = $this->RCONClient->request(new RCON($id, MinecraftTypes::AUTH_REQUEST, $password));

        if($response->getId() !== 0){
            throw new MinecraftClientException("Invalid password");
        }

        if($response->getType() !== MinecraftTypes::AUTH_RESPONSE){
            throw new MinecraftClientException("Invalid password");
        }

        return $response;
    }

    /**
     * @throws RCONException
     */
    public function sendCommand(string $command, int $id = 0): RCON
    {
        $response = $this->RCONClient->request(new RCON($id, MinecraftTypes::COMMAND_REQUEST, $command));

        if($response->getId() !== $id){
            throw new MinecraftClientException("Not authorized");
        }

        if($response->getType() !== MinecraftTypes::COMMAND_RESPONSE){
            throw new MinecraftClientException("Not authorized");
        }

        return $response;
    }

    public function getRconClient(): RCONClient
    {
        return $this->RCONClient;
    }

}