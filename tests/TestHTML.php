<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;

class TestHTML
{
	private $domFinder;

	function __construct()
	{
		$this->domFinder = new DOMFinder(getSample('sample-one.html'));
	}

	public function process()
	{
		$sectionItems 	= $this->domFinder->findByClass('section-item')->all();
		if($sectionItems->length) {
			foreach($sectionItems as $sik => $sectionItem) {
				echo $sectionItem->outerHTML()."\n";
			}
		}
	}
}