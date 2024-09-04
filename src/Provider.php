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
    const USER_DATASTORE_FILE = '/../data/users.json';
    const DONATION_DATASTORE_FILE = '/../data/donations.json';


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
        if (!empty($fileContents = file_get_contents($this->getUserDataStoreFile()))) {
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
        $file = $this->getUserDataStoreFile();

        $users = json_decode(@file_get_contents($file) ?: '[]', associative: true);

        $users[$user->getId()] = $user->jsonSerialize();

        return @file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT)) !== false;
    }

    private function getUserDataStoreFile(): string
    {
        return __DIR__ . self::USER_DATASTORE_FILE;
    }

    public function saveDonation(Donation $donation): bool
    {
        return $this->saveDonationDataToDataStore($donation);
    }

    private function saveDonationDataToDataStore(Donation $donation): bool
    {
        $datetime = new \DateTime('now', new \DateTimeZone('UTC'));

        $file = $this->getDonationDataStoreFile();

        $donations = json_decode(@file_get_contents($file) ?: '[]', associative: false);
        $donation->setId(count($donations) . ''); // AUTO_INCREMENT
        $donation->setTimestamp($datetime->format('Y-m-d H:i:s'));

        $donations[] = $donation->jsonSerialize();
        return @file_put_contents($file, json_encode($donations, JSON_PRETTY_PRINT)) !== false;
    }


    private function getDonationDataStoreFile(): string
    {
        return __DIR__ . self::DONATION_DATASTORE_FILE;
    }
}