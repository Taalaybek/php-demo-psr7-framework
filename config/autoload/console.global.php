<?php

use App\Console\Command;
use Psr\Container\ContainerInterface;

return [
    'dependencies' => [
        'factories' => [
            Command\CacheClearCommand::class => function (ContainerInterface $container) {
                return new Command\CacheClearCommand(
                    $container->get('config')['console']['cachePaths']
                );
            },
        ],
    ],
    'console' => [
        'commands' => [
            Command\CacheClearCommand::class
        ],
        'cachePaths' => [
            'twig' => 'var/cache/twig',
        ],
    ],
];
