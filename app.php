#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Fifteen\Game;
use Fifteen\PlayCommand;
use Symfony\Component\Console\Application;

$app = new Application('Game of fifteen'. '1.0');

$app->add(new PlayCommand(new Game));

$app->run();