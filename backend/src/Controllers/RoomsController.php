<?php
declare(strict_types=1);
namespace Poker\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Poker\Domain\{Room, RoomRepository};

final class RoomsController
{
    public function list(ServerRequestInterface $request): JsonResponse
    {
        $items = RoomRepository::getInstance()->all();
        return new JsonResponse(['data' => array_map(fn(Room $r) => $r->jsonSerialize(), $items)]);
    }

    public function create(ServerRequestInterface $request): JsonResponse
    {
        $payload = json_decode((string)($request->getBody()), true) ?? [];
        $name = trim((string)($payload['name'] ?? ''));
        if ($name === '') {
            return new JsonResponse(['error' => 'name is required'], 422);
        }

        $room = RoomRepository::getInstance()->create($name);
        return new JsonResponse(['data' => $room->jsonSerialize()], 201);
    }
}
