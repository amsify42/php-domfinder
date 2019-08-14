<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;


class TestXMLURL
{
	private $domFinder;

	function __construct()
	{
		$this->domFinder = new DOMFinder();
		$this->domFinder->loadXML('https://www.apple.com/newsroom/rss-feed.rss', true);
	}

	public function process()
	{
		$entries = $this->domFinder->getElements('entry');
		var_dump($entries->length);
	}
}