<?php

namespace SoftwareChallenge\Mid;

use JsonSerializable;

class User implements JsonSerializable
{
    public function __construct(
        private readonly ?string $id,
        private readonly ?string $email
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail()
        ];
    }
}