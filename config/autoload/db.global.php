<?php

return [
    'dependencies' => [
        'factories' => [
            PDO::class => Infrastructure\App\PDOFactory::class,
        ]
    ],
];
