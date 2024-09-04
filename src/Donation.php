<?php

namespace SoftwareChallenge\Mid;

use JsonSerializable;

class Donation implements JsonSerializable
{
    private string $id;

    private string $timestamp;

    public function __construct(
        private readonly ?string $userId,
        private readonly ?float $amountUSD,
        private readonly ?string $orgId,
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getAmountUSD(): ?float
    {
        return $this->amountUSD;
    }

    public function getOrgId(): ?string
    {
        return $this->orgId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'timestamp' => $this->getTimestamp(),
            'amount_USD' => $this->getAmountUSD(),
            'org_id' => $this->getOrgId()
        ];
    }
}