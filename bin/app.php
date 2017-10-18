#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$cli = new Application('Application console');

$cli->run();
