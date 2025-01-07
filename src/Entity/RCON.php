<?php

declare(strict_types=1);

namespace AnvilM\RCON\Entity;

class RCON
{
    /** @var int $id */
    private $id;

    /** @var int $type */
    private $type;

    /** @var string $body */
    private $body;

    public function __construct(int $id, int $type, string $body){
        $this->id = $id;
        $this->type = $type;
        $this->body = $body;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getBody(): string
    {
        return $this->body;
    }



}