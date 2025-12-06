<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use App\Repositories\BookRepository;
use Psr\Container\ContainerInterface;
use App\Repositories\AuthorRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
         AuthorRepository::class => function (ContainerInterface $c) {
            return new AuthorRepository($c->get('db'));
        },

        BookRepository::class => function (ContainerInterface $c) {
            return new BookRepository($c->get('db'));
        },
    ]);
};