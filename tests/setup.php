<?php

require_once __DIR__.'/../app/autoload.php';

echo exec('php '.__DIR__.'/../bin/console doctrine:database:drop --force --env test');
echo exec('php '.__DIR__.'/../bin/console doctrine:database:create --env test');
echo exec('php '.__DIR__.'/../bin/console doctrine:schema:update --force --env test');
echo exec('php '.__DIR__.'/../bin/console doctrine:database:import --env test '.__DIR__.'/../data/colors.sql');
echo exec('php '.__DIR__.'/../bin/console doctrine:database:import --env test '.__DIR__.'/../data/plants.sql');
