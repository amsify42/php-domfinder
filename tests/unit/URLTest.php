<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class URLTest extends TestCase
{
	
	public function testLength()
	{
		libxml_use_internal_errors(true);
		$domFinder = new DOMFinder('https://goop.com/wellness/health/best-sources-of-collagen/');
		$this->assertEquals(29, $domFinder->metaTags()->length);
	}
}