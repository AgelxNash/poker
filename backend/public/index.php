
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use function FastRoute\simpleDispatcher;
use Poker\Controllers\HealthController;
use Poker\Controllers\RoomsController;

$request = ServerRequestFactory::fromGlobals();

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/api/health', [HealthController::class, 'index']);
    $r->addRoute('GET', '/api/rooms', [RoomsController::class, 'list']);
    $r->addRoute('POST', '/api/rooms', [RoomsController::class, 'create']);
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $res = new JsonResponse(['error' => 'Not found'], 404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $res = new JsonResponse(['error' => 'Method not allowed'], 405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $handler = new $class();
        $res = $handler->$method($request);
        if (!$res instanceof Psr\Http\Message\ResponseInterface) {
            $res = new JsonResponse($res, 200);
        }
        break;
}

$emitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
$emitter->emit($res);
