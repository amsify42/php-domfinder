<?php

require_once __DIR__.'/../vendor/autoload.php';

libxml_use_internal_errors(true);

$testHtml = new Amsify42\Tests\TestHTML();
$testHtml->process();
echo "\n\n";

$testXml = new Amsify42\Tests\TestXML();
$testXml->process();
echo "\n\n";

$testXmlUrl = new Amsify42\Tests\TestXMLURL();
$testXmlUrl->process();
echo "\n\n";

$testUrl = new Amsify42\Tests\TestURL();
$testUrl->process();

$testRegex = new Amsify42\Tests\TestRegex();
$testRegex->process();
echo "\n\n";

$testLike = new Amsify42\Tests\TestLike();
$testLike->process();
echo "\n\n";