<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;


class TestURL
{
	private $domFinder;

	function __construct()
	{
		$this->domFinder = new DOMFinder('https://goop.com/wellness/health/best-sources-of-collagen/');
	}

	public function process()
	{
		var_dump($this->domFinder->metaTags()->length);
	}
}