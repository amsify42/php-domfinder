<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class XMLTest extends TestCase
{

	public function testXmlItem()
	{
		$domFinder 	= new DOMFinder(getSample('books.xml'));
		$books 		= $domFinder->getElements('book');
		if($books->length)
		{
			foreach($books as $book)
			{
				$this->assertStringEndsWith('</book>', $domFinder->getHtml($book));
			}
		}
		$book = $domFinder->find('book')->byId('bk101')->first();
		if($book)
		{
			$this->assertStringStartsWith('<book id="bk101">', $domFinder->getHtml($book));
		}
	}
}