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

    public function createDonation(string $requestBody): Donation 
    {
        $donationData = json_decode($requestBody, associative: true, flags: JSON_THROW_ON_ERROR);

        $donation = new Donation(
            userId: $donationData['user_id'] ?? null,
            amountUSD: $donationData['amount_USD'] ?? 0.0,
            orgId: $donationData['org_id'] ?? null
        );
        
        if (empty($this->provider->getUser($donation->getUserId()))) {
            throw new Exception('User Not Found');
        }

        if ($donation->getAmountUSD() <= 0) {
            throw new Exception('Donation Amount Invalid');
        }

        if (empty($donation->getOrgId())) {
            throw new Exception('Org Id Invalid');
        }
        
        $this->provider->saveDonation($donation);

        return $donation;
    }

}