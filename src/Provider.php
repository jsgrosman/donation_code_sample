<?php

namespace SoftwareChallenge\Mid;

/**
 * This class is intended to act as abstraction layer that interacts with the underlying datasource. For the
 * purposes of this challenge, the datasource is a simple JSON file stored in the "data" directory. Example:
 *
    {
        "7": {
            "id": "7",
            "email": "a@b.com"
        },
        "4": {
            "id": "4",
            "email": "c@d.com"
        }
    }
 *
 */
class Provider
{
    const DATASTORE_FILE = '/../data/users.json';

    public function getUser(string $userId): ?User
    {
        if (!is_null($userData = $this->loadUserDataFromDataStore($userId))) {
            return new User(
                id: $userData['id'] ?? null,
                email: $userData['email'] ?? null
            );
        }

        return null;
    }

    public function saveUser(User $user): bool
    {
        return $this->saveUserDataToDataStore($user);
    }

    /**
     * @return array|null associative array of user data, null if not found
     */
    private function loadUserDataFromDataStore(string $userId): ?array
    {
        if (!empty($fileContents = file_get_contents($this->getDataStoreFile()))) {
            $users = json_decode($fileContents, associative: true);

            return $users[$userId] ?? null;
        }

        return null;
    }

    /**
     * Caution: this method overwrites user data for existing users.
     */
    private function saveUserDataToDataStore(User $user): bool
    {
        $file = $this->getDataStoreFile();

        $users = json_decode(@file_get_contents($file) ?: '[]', associative: true);

        $users[$user->getId()] = $user->jsonSerialize();

        return @file_put_contents($file, json_encode($users)) !== false;
    }

    private function getDataStoreFile(): string
    {
        return __DIR__ . self::DATASTORE_FILE;
    }
}