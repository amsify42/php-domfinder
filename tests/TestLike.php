<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;

class TestLike
{
	private $domFinder;

	function __construct()
	{
		$this->domFinder = new DOMFinder(getSample('sample-four.html'));
	}

	public function process()
	{
		$this->classLike();
		$this->idLike();
		$this->attrLike();
	}

	private function classLike()
	{
		$divs 		= $this->domFinder->find('div')->classLike('child-class-')->all();
		if($divs->length)
		{
			echo $divs->length."\n";
		}

		$uls 		= $this->domFinder->find('ul')->classLike('list-')->all();
		if($uls->length)
		{
			echo $uls->length."\n";
			foreach($uls as $ul)
			{
				$lis = $ul->findClassLike('item-')->all();
				if($lis->length)
				{
					echo $lis->length."\n";
					foreach($lis as $li)
					{
						echo $li->textContent."\n";
					}
				} 
			}
		}
	}

	private function idLike()
	{
		$uls 		= $this->domFinder->find('ul')->idLike('some-')->all();
		if($uls->length)
		{
			echo $uls->length."\n";
			foreach($uls as $ul)
			{
				$lis = $ul->findIdLike('item-')->all();
				if($lis->length)
				{
					echo $lis->length."\n";
					foreach($lis as $li)
					{
						echo $li->textContent."\n";
					}
				} 
			}
		}
		$div = $this->domFinder->findFirstIdLike('unique-');
		if($div)
		{
			echo $div->outerHTML()."\n";
		}
	}

	private function attrLike()
	{
		$uls 		= $this->domFinder->find('ul')->attrLike('my-att', 'some-')->all();
		if($uls->length)
		{
			echo $uls->length."\n";
			foreach($uls as $ul)
			{
				$lis = $ul->findAttrLike('my-att', 'item-')->all();
				if($lis->length)
				{
					echo $lis->length."\n";
					foreach($lis as $li)
					{
						echo $li->textContent."\n";
					}
				} 
			}
		}
		$div = $this->domFinder->findFirstAttrLike('my-att', 'unique-');
		if($div)
		{
			echo $div->outerHTML()."\n";
		}
	}
}