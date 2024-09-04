<?php

namespace SoftwareChallenge\Mid\Tests;

use JsonException;
use PHPUnit\Framework\TestCase;
use SoftwareChallenge\Mid\Controller;
use SoftwareChallenge\Mid\Provider;
use SoftwareChallenge\Mid\User;

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
}
