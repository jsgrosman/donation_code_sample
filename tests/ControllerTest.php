<?php

namespace SoftwareChallenge\Mid\Tests;

use JsonException;
use Exception;
use PHPUnit\Framework\TestCase;
use SoftwareChallenge\Mid\Controller;
use SoftwareChallenge\Mid\Provider;
use SoftwareChallenge\Mid\User;
use SoftwareChallenge\Mid\Donation;


class ControllerTest extends TestCase
{
    public function testNewUserCreationForValidRequest(): void
    {
        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveUser')->willReturn(true);

        $user = (new Controller($providerMock))->createUser('{"id":1,"email":"a"}');

        $this->assertInstanceOf(User::class, $user);
    }

    public function testNewUserCreationForInValidRequestBody(): void
    {
        $this->expectException(JsonException::class);

        $mockProvider = $this->createMock(Provider::class);

        (new Controller($mockProvider))->createUser('invalid-json');
    }

    public function testNewDonationCreationForValidRequest(): void
    {
        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveDonation')->willReturn(true);
        $providerMock->method('getUser')->willReturn(new User('4', 'a@a.com'));


        $donation = (new Controller($providerMock))->createDonation('{"user_id":"4", "amount_USD": 7.50, "org_id": "350"}');

        $this->assertInstanceOf(Donation::class, $donation);
        $this->assertEquals("4", $donation->getUserId());
        $this->assertEquals(7.5, $donation->getAmountUSD());
        $this->assertEquals("350", $donation->getOrgId());
    }

    public function testNewDonationCreationForInValidRequestBody(): void
    {
        $this->expectException(JsonException::class);

        $mockProvider = $this->createMock(Provider::class);

        (new Controller($mockProvider))->createDonation('invalid-json');
    }

    public function testNewDonationCreationForNonExistentUser(): void
    {
        $this->expectException(Exception::class);

        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveDonation')->willReturn(true);
        $providerMock->method('getUser')->willReturn(null);

        $donation = (new Controller($providerMock))->createDonation('{"user_id":"4", "amount_USD": 7.50, "org_id": "350"}');
    }

    public function testNewDonationCreationForNegativeAmount(): void
    {
        $this->expectException(Exception::class);

        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveDonation')->willReturn(true);
        $providerMock->method('getUser')->willReturn(new User('4', 'a@a.com'));

        $donation = (new Controller($providerMock))->createDonation('{"user_id":"4", "amount_USD": -7.50, "org_id": "350"}');
    }

    public function testNewDonationCreationForZeroAmount(): void
    {
        $this->expectException(Exception::class);

        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveDonation')->willReturn(true);
        $providerMock->method('getUser')->willReturn(new User('4', 'a@a.com'));

        $donation = (new Controller($providerMock))->createDonation('{"user_id":"4", "amount_USD": 0, "org_id": "350"}');
    }

    public function testNewDonationCreationForNoOrgId(): void
    {
        $this->expectException(Exception::class);

        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('saveDonation')->willReturn(true);
        $providerMock->method('getUser')->willReturn(new User('4', 'a@a.com'));

        $donation = (new Controller($providerMock))->createDonation('{"user_id":"4", "amount_USD": 7.50, "org_id": ""}');
    }
}
