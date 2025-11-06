<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

/** CORS (dev) */
$app->add(function (Request $request, Response $handler) {
    $response = $handler;
    $response = $response->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    if ($request->getMethod() === 'OPTIONS') {
        return $response->withStatus(204);
    }
    return $response;
});

$app->get('/api/health', function (Request $req, Response $res) {
    $res->getBody()->write(json_encode(['status' => 'ok']));
    return $res->withHeader('Content-Type', 'application/json');
});

$store = new Poker\Infrastructure\JsonStore(__DIR__ . '/../var/data/sessions.json');

$app->post('/api/sessions', function (Request $req, Response $res) use ($store) {
    $data = json_decode((string)$req->getBody(), true) ?: [];
    $room = $data['room'] ?? null;
    $deck = $data['deck'] ?? 'fibonacci';
    if (!$room) {
        $res->getBody()->write(json_encode(['error' => 'room is required']));
        return $res->withStatus(422)->withHeader('Content-Type', 'application/json');
    }
    $session = Poker\Domain\Session::create($room, $deck);
    $store->upsertSession($session);
    $res->getBody()->write(json_encode($session->toArray()));
    return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->get('/api/sessions/{id}', function (Request $req, Response $res, array $args) use ($store) {
    $id = $args['id'];
    $session = $store->getSession($id);
    if (!$session) {
        $res->getBody()->write(json_encode(['error' => 'not found']));
        return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    $res->getBody()->write(json_encode($session->toArray()));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->post('/api/sessions/{id}/vote', function (Request $req, Response $res, array $args) use ($store) {
    $id = $args['id'];
    $payload = json_decode((string)$req->getBody(), true) ?: [];
    $user = $payload['user'] ?? null;
    $value = $payload['value'] ?? null;
    if (!$user or $value === null) {
        $res->getBody()->write(json_encode(['error' => 'user and value are required']));
        return $res->withStatus(422)->withHeader('Content-Type', 'application/json');
    }
    $session = $store->getSession($id);
    if (!$session) {
        $res->getBody()->write(json_encode(['error' => 'not found']));
        return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    if ($session->status === 'finalized') {
        $res->getBody()->write(json_encode(['error' => 'session finalized']));
        return $res->withStatus(409)->withHeader('Content-Type', 'application/json');
    }
    $session->castVote($user, (string)$value);
    $store->upsertSession($session);
    $res->getBody()->write(json_encode($session->toArray()));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->post('/api/sessions/{id}/finalize', function (Request $req, Response $res, array $args) use ($store) {
    $id = $args['id'];
    $session = $store->getSession($id);
    if (!$session) {
        $res->getBody()->write(json_encode(['error' => 'not found']));
        return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    $session->finalize();
    $store->upsertSession($session);
    $res->getBody()->write(json_encode($session->toArray()));
    return $res->withHeader('Content-Type', 'application/json');
});

// Ensure var dir exists
@mkdir(__DIR__ . '/../var/data', 0777, true);

$app->run();
