<?php

use App\Controllers\CondominiumController;
use App\Controllers\UnitController;
use App\Controllers\ReservationController;
use App\Controllers\PlaceController;
use App\Http\Response\ResponseBuilder;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $data = ['php' => 'Current PHP version running is: ' . phpversion()];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    });

    $app->group('/condominiums', function (RouteCollectorProxy $group) {
        $group->get('', [CondominiumController::class, 'list']);
        $group->get('/{id}', [CondominiumController::class, 'find']);
        $group->post('', [CondominiumController::class, 'create']);
        $group->put('/{id}', [CondominiumController::class, 'update']);
        $group->delete('/{id}', [CondominiumController::class, 'delete']);
    });

    $app->group('/units', function (RouteCollectorProxy $group) {
        $group->get('', [UnitController::class, 'list']);
        $group->get('/{id}', [UnitController::class, 'find']);
        $group->post('', [UnitController::class, 'create']);
        $group->put('/{id}', [UnitController::class, 'update']);
        $group->delete('/{id}', [UnitController::class, 'delete']);
    });

    $app->group('/reservations', function (RouteCollectorProxy $group) {
        $group->get('', [ReservationController::class, 'list']);
        $group->post('', [ReservationController::class, 'create']);
        $group->get('/{id}', [ReservationController::class, 'find']);
        $group->put('/{id}', [ReservationController::class, 'update']);
        $group->delete('/{id}', [ReservationController::class, 'delete']);
    });

    $app->group('/places', function (RouteCollectorProxy $group) {
        $group->get('', [PlaceController::class, 'list']);
        $group->get('/{id}', [PlaceController::class, 'find']);
        $group->post('', [PlaceController::class, 'create']);
        $group->put('/{id}', [PlaceController::class, 'update']);
        $group->delete('/{id}', [PlaceController::class, 'delete']);
    });
};
