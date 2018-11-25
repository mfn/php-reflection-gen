#!/usr/bin/env php
<?php declare(strict_types=1);

use Mfn\PHP\Reflection\Generator\Console\Generator;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../bootstrap.php';

$app = new Application();
$app->setCatchExceptions(false);
$app->add(new Generator());
return $app->run();
