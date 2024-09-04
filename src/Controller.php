<?php

namespace SoftwareChallenge\Mid;

use Exception;

class Controller
{
    private Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @throws Exception
     */
    public function getUser(?string $userId): ?User
    {
        if (is_null($userId)) {
            throw new Exception('User ID cannot be null');
        }

        return $this->provider->getUser($userId);
    }

    /**
     * @throws Exception
     */
    public function createUser(string $requestBody): User
    {
        $userData = json_decode($requestBody, associative: true, flags: JSON_THROW_ON_ERROR);

        $user = new User(
            id: $userData['id'] ?? null,
            email: $userData['email'] ?? null
        );

        $this->provider->saveUser($user);

        return $user;
    }
}