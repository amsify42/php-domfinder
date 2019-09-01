<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class XMLURLTest extends TestCase
{

	public function testLength()
	{
		libxml_use_internal_errors(true);
		$domFinder 	= new DOMFinder();
		$domFinder->loadXML('https://www.apple.com/newsroom/rss-feed.rss', true);
		$entries 	= $domFinder->getElements('entry');
		$this->assertEquals(20, $entries->length);
	}
}