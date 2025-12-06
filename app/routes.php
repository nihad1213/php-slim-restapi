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

    $app->get('/authors/{id}', function (Request $request, Response $response, array $args) {
        $repo = $this->get(AuthorRepository::class);
        $author = $repo->getById((int) $args['id']);
        
        if (!$author) {
            $response->getBody()->write(json_encode(['error' => 'Author not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        
        $response->getBody()->write(json_encode($author));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/authors', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        
        if (empty($data['name'])) {
            $response->getBody()->write(json_encode(['error' => 'Name is required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $repo = $this->get(AuthorRepository::class);
        $id = $repo->create($data);
        
        $response->getBody()->write(json_encode(['id' => $id, 'message' => 'Author created']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->put('/authors/{id}', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        
        if (empty($data['name'])) {
            $response->getBody()->write(json_encode(['error' => 'Name is required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $repo = $this->get(AuthorRepository::class);
        $success = $repo->update((int) $args['id'], $data);
        
        if (!$success) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update author']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
        
        $response->getBody()->write(json_encode(['message' => 'Author updated']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/authors/{id}', function (Request $request, Response $response, array $args) {
        $repo = $this->get(AuthorRepository::class);
        $success = $repo->delete((int) $args['id']);
        
        if (!$success) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete author']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
        
        $response->getBody()->write(json_encode(['message' => 'Author deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
