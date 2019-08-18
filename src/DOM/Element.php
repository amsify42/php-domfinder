<?php

namespace Amsify42\DOMFinder\DOM;

use DOMElement;
use Amsify42\DOMFinder\DOMFinder;
use Amsify42\DOMFinder\Helper\Html;

class Element extends DOMElement
{
	private $outerHTML, $domFinder;

	function __call($method, $args)
	{
		if(!$this->domFinder) {
			$this->setOuterHTML();
			$this->domFinder = new DOMFinder($this->outerHTML, 'html');
		}
		if(method_exists($this->domFinder, $method) && is_callable([$this->domFinder, $method])) {
			return call_user_func_array(array($this->domFinder, $method), $args);
		}
	}

	public function outerHTML()
	{
		$this->setOuterHTML();
		return $this->outerHTML;
	}

	public function innerHTML()
	{
    	return implode(array_map([$this->ownerDocument, 'saveHTML'], iterator_to_array($this->childNodes)));
	}

	public function extractByRegex($patterns, $multi = false)
	{
		$this->setOuterHTML();
		return Html::extractByRegex($this->outerHTML, $patterns, $multi);
	}

	private function setOuterHTML()
	{
		if(!$this->outerHTML) {
			$this->outerHTML = 	$this->ownerDocument->saveHTML($this);
		}
	}
}