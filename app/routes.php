<?php

declare(strict_types=1);

use Slim\App;
use App\Repositories\BookRepository;
use App\Repositories\AuthorRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/test-db', function ($request, $response) {
        $db = $this->get('db');
        $stmt = $db->query("SELECT 1");
        $data = $stmt->fetch();

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/authors', function ($request, $response) {
        $repo = $this->get(AuthorRepository::class);
        $data = $repo->getAll();

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/books', function ($request, $response) {
        $repo = $this->get(BookRepository::class);
        $data = $repo->getAll();

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
