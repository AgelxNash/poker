
<?php

declare(strict_types=1);

namespace Poker\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class HealthController
{
    public function index(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'service' => 'poker-backend',
            'time' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ]);
    }
}
