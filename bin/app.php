#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require 'config/container.php';

$cli = new Application('Application console');

$entityManager = $container->get(\Doctrine\ORM\EntityManagerInterface::class);
$connection = $entityManager->getConnection();

$configuration = new \Doctrine\DBAL\Migrations\Configuration\Configuration($connection);
$configuration->setMigrationsDirectory('db/migrations');
$configuration->setMigrationsNamespace('Migration');

$cli->setHelperSet(new Symfony\Component\Console\Helper\HelperSet([
    'db' => new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection),
    'em' => new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
    'dialog' => new Symfony\Component\Console\Helper\QuestionHelper(),
    'configuration' => new Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper($connection, $configuration),
]));

Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $cli->add($container->get($command));
}

$cli->run();
