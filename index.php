<?php

namespace SoftwareChallenge\Mid;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use SoftwareChallenge\Mid\Controller;
use SoftwareChallenge\Mid\Provider;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/v1/users/{userId}', function (Request $request, Response $response, array $args) {
    $userId = $args['userId'] ?? null;

    $controller = new Controller(new Provider());
    $user = $controller->getUser($userId);

    return getUserViewResponse($user, $response);
});

$app->post('/v1/users', function (Request $request, Response $response) {
    $controller = new Controller(new Provider());
    $user = $controller->createUser((string) $request->getBody());

    return getUserViewResponse($user, $response);
});

$app->post('/v1/donations', function (Request $request, Response $response) {

    try {
        $controller = new Controller(new Provider());
        $donation = $controller->createDonation((string) $request->getBody());

        return getDonationViewResponse($donation, $response);
    } catch (\Exception $e) {
        $response->getBody()->write(
            json_encode(['error' => $e->getMessage()])
        );   
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
    }
});

function getUserViewResponse(?User $user, Response $response): Response
{
    $payload = json_encode($user, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
}

function getDonationViewResponse(?Donation $donation, Response $response): Response
{
    $payload = json_encode($donation, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
}

$app->run();