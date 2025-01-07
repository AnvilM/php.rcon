<?php

declare(strict_types=1);

namespace AnvilM\RCON\Clients;

use AnvilM\RCON\Clients\Minecraft\Exceptions\MinecraftClientException;
use AnvilM\RCON\Clients\Minecraft\MinecraftTypes;
use AnvilM\RCON\Entity\RCON;
use AnvilM\RCON\RCONClient;
use AnvilM\RCON\RCONException;

interface ClientContract
{

    public function __construct(string $host, int $port);

    public function authenticate(string $password, int $id = 0): RCON;

    public function sendCommand(string $command, int $id = 0): RCON;

    public function getRconClient(): RCONClient;
}