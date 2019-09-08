<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class LikeTest extends TestCase
{
	private $domFinder;

	public function testLikes()
	{
		$this->domFinder = new DOMFinder(getSample('sample-four.html'));
		$this->classLike();
		$this->idLike();
		$this->attrLike();
	}

	private function classLike()
	{
		$divs 		= $this->domFinder->find('div')->classLike('child-class-')->all();
		if($divs->length)
		{
			$this->assertEquals(2, $divs->length);
		}

		$uls 		= $this->domFinder->find('ul')->classLike('list-')->all();
		if($uls->length)
		{
			$this->assertEquals(2, $uls->length);
			foreach($uls as $ul)
			{
				$lis = $ul->findClassLike('item-')->all();
				if($lis->length)
				{
					$this->assertEquals(3, $lis->length);
				} 
			}
		}
	}

	private function idLike()
	{
		$uls 		= $this->domFinder->find('ul')->idLike('some-')->all();
		if($uls->length)
		{
			$this->assertEquals(2, $uls->length);
			foreach($uls as $ul)
			{
				$lis = $ul->findIdLike('item-')->all();
				if($lis->length)
				{
					$this->assertEquals(3, $lis->length);
				} 
			}
		}
		$this->assertNotNull($this->domFinder->findFirstIdLike('unique-'));
	}

	private function attrLike()
	{
		$uls 		= $this->domFinder->find('ul')->attrLike('my-att', 'some-')->all();
		if($uls->length)
		{
			$this->assertEquals(2, $uls->length);
			foreach($uls as $ul)
			{
				$lis = $ul->findAttrLike('my-att', 'item-')->all();
				if($lis->length)
				{
					$this->assertEquals(3, $lis->length);
				} 
			}
		}
		$this->assertNotNull($this->domFinder->findFirstAttrLike('my-att', 'unique-'));
	}
}