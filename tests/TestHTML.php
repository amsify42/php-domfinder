<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;

class TestHTML
{

	public function process()
	{
		$this->sampleOne();
		$this->sampleTwo();
		$this->sampleThree();
		$this->sampleFour();
	}

	private function sampleOne()
	{
		$domFinder 		= new DOMFinder(getSample('sample-one.html'));
		$sectionItems 	= $domFinder->findByClass('section-item')->all();
		if($sectionItems->length) {
			foreach($sectionItems as $sik => $sectionItem) {
				echo $sectionItem->outerHTML()."\n";
			}
		}	
	}

	private function sampleTwo()
	{
		$domFinder 		= new DOMFinder(getSample('sample-two.html'));
		$sectionItems 	= $domFinder->findFirstByClass('section-items');
		echo $sectionItems->findFirst('p')->outerHTML();
		echo "\n\n";
		echo $sectionItems->getElement('p', 1)->outerHTML();
	}

	private function sampleThree()
	{
		$domFinder 	= get_dom_finder(getSample('sample-three.html'));
		$div 		= $domFinder->find('div')->byClass('parent-class')->first();
		if($div) {
			$divs = $div->find('div')->byClass('child-class')->all();
			if($divs->length) {
				echo $divs->length."\n";
			}
		}
	}

	private function sampleFour()
	{
		$domFinder 	= get_dom_finder(getSample('sample-three.html'));
		$divs 		= $domFinder->finder()->query("/div[@class='parent-class']/div");
		if($divs->length) {
			echo $divs->length."\n";
		}
	}
}