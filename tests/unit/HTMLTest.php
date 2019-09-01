<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class HTMLTest extends TestCase
{

	public function testSamples()
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
		if($sectionItems->length)
		{
			foreach($sectionItems as $sik => $sectionItem)
			{
				$this->assertStringEndsWith('</div>', $sectionItem->outerHTML());
			}
		}	
	}

	private function sampleTwo()
	{
		$domFinder 		= new DOMFinder(getSample('sample-two.html'));
		$sectionItems 	= $domFinder->findFirstByClass('section-items');
		$this->assertStringEndsWith('content one', trim($sectionItems->findFirst('p')->innerHTML()));
		$this->assertStringEndsWith('</p>', trim($sectionItems->getElement('p', 1)->outerHTML()));
	}

	private function sampleThree()
	{
		$domFinder 	= get_dom_finder(getSample('sample-three.html'));
		$div 		= $domFinder->find('div')->byClass('parent-class')->first();
		if($div)
		{
			$divs = $div->find('div')->byClass('child-class')->all();
			if($divs->length)
			{
				$this->assertEquals(2, $divs->length);
			}
		}
	}

	private function sampleFour()
	{
		$domFinder 	= get_dom_finder(getSample('sample-three.html'));
		$divs 		= $domFinder->finder()->query("/div[@class='parent-class']/div");
		if($divs->length)
		{
			$this->assertEquals(2, $divs->length);
		}
	}
}