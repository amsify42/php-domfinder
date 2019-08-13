<?php

require_once __DIR__.'/../vendor/autoload.php';

libxml_use_internal_errors(true);

$testHtml = new Amsify42\Tests\TestHTML();
$testHtml->process();