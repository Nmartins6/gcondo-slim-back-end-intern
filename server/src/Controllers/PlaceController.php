<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Services\PlaceService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PlaceController
{
    public function __construct(private PlaceService $service) {}

    public function list(Request $request, Response $response): Response
    {
        $data = ['places' => $this->service->list()];
        return ResponseBuilder::respondWithData($response, data: $data);
    }

    public function find(Request $request, Response $response, array $args): Response
    {
        $data = ['place' => $this->service->find($args['id'])];
        return ResponseBuilder::respondWithData($response, data: $data);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        
        $data = ['place' => $this->service->create($body)];
        return ResponseBuilder::respondWithData($response, HttpStatus::Created, $data);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();
        
        $data = ['place' => $this->service->update($args['id'], $body)];
        return ResponseBuilder::respondWithData($response, data: $data);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $deleted = $this->service->delete($args['id']);
        
        $status = $deleted ? HttpStatus::OK : HttpStatus::ServerError;
        return ResponseBuilder::respondWithData($response, $status);
    }
}
