<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;


class TestXML
{
	private $domFinder;

	function __construct()
	{
		$this->domFinder = new DOMFinder(getSample('books.xml'));
	}

	public function process()
	{
		$books 	= $this->domFinder->getElements('book');
		if($books->length) {
			foreach($books as $book) {
				echo $this->domFinder->getHtml($book);
			}
		}
		echo "\n\n";
		$book 	= $this->domFinder->find('book')->byId('bk101')->first();
		if($book) {
			echo $this->domFinder->getHtml($book);
		}
	}
}