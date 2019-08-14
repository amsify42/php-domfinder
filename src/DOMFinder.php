<?php

namespace Amsify42\DOMFinder;

use DOMDocument;
use DomXPath;


class DOMFinder
{
	private $dom, $finder;

	private $headers 		= [];

	private $metaTags 		= NULL;
	private $targetElement 	= '';
	private $compareType 	= '';
	private $targetAttr 	= '';
	private $targetAttrVal 	= '';
	private $subElements 	= '';

	function __construct($source=NULL, $type='file', $loadContent=false)
	{
		if($source) $this->loadByType($source, $type, $loadContent);
	}

	public function setHeaders($headers = [])
	{
		$this->headers = $headers;
	}

	public function load($source)
	{
		$this->loadByType($source);
	}

	public function loadHTML($html, $loadContent=false)
	{
		$this->loadByType($html, 'html', $loadContent);
	}

	public function loadXML($xml, $loadContent=false)
	{
		$this->loadByType($xml, 'xml', $loadContent);
	}

	private function loadByType($source, $type='file', $loadContent=false)
	{
		$this->metaTags = NULL;
		$this->dom 		= new DOMDocument;
		if($type == 'html') {
			$this->dom->loadHTML(($loadContent)? file_get_contents($source): $source);
		} else if($type == 'xml') {
			$this->dom->loadXML(($loadContent)? file_get_contents($source): $source);
		} else {
			if(filter_var($source, FILTER_VALIDATE_URL)) {
				if($this->getURLType($source) == 'xml') {
					$this->dom->loadXML(file_get_contents($source));	
				} else {
					$this->dom->loadHTML(file_get_contents($source));
				}
			} else {
				$this->dom->load($source);	
			}
		}
		$this->finder 	= new DomXPath($this->dom);
	}

	private function getURLType($url)
	{
		$info = pathinfo($url);
		return isset($info['extension'])? trim($info['extension']): 'html';
	}

	public function dom()
	{
		return $this->dom;
	}

	public function finder()
	{
		return $this->finder;
	}

	public function getElements($tag='*')
	{
		return $this->dom->getElementsByTagName($tag);
	}

	public function getFirstElement($tag='*')
	{
		$result = $this->dom->getElementsByTagName($tag);
		if($result->length) {
			return $result->item(0);
		}
		return NULL;
	}

	public function getElement($tag='*', $index=0)
	{
		$result = $this->dom->getElementsByTagName($tag);
		if($result->length) {
			return $result->item($index);
		}
		return NULL;
	}

	public function metaTags()
	{
		$this->setMetaTags();
		return $this->metaTags;
	}

	public function getMetaValue($attr, $key, $value='content')
	{
		$this->setMetaTags();
		if($this->metaTags && $this->metaTags->length) {
			foreach($this->metaTags as $metaTag) {
				if($metaTag->getAttribute($attr) == $key) {
					return $metaTag->getAttribute($value);
				}
			}
		}
		return '';
	}

	public function findAll($element = '*')
	{
		return $this->find($element)->all();
	}

	public function findFirst($element = '*')
	{
		return $this->find($element)->first();
	}

	public function findById($id)
	{
		return $this->find()->byId($id);
	}

	public function findByClass($class)
	{
		return $this->find()->byClass($class);
	}

	public function findClassLike($class)
	{
		return $this->find()->classLike($class);
	}

	public function findByAttr($type, $value)
	{
		return $this->find()->byAttr($type, $value);
	}

	public function find($element = '*')
	{
		if($this->targetElement) {
			$this->subElements .= '/'.$element;
		} else {
			$this->targetElement = $element;
		}
		return $this;
	}

	public function byId($value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= 'id';
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function byClass($value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= 'class';
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function byAttr($type, $value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= $type;
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function idLike($value)
	{
		$this->compareType 		= 'contains';
		$this->targetAttr 		= 'id';
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function classLike($value)
	{
		$this->compareType 		= 'contains';
		$this->targetAttr 		= 'class';
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function attrLike($type, $value)
	{
		$this->compareType 		= 'contains';
		$this->targetAttr 		= $type;
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function first()
	{
		$result = $this->result();
		if($result->length) {
			return $result->item(0);
		}
		return NULL;
	}

	public function get($index=0)
	{
		$result = $this->result();
		if($result->length) {
			return $result->item($index);
		}
		return NULL;
	}

	public function all()
	{
		return $this->result();
	}

	public function getHtml($item)
	{
		return $this->dom->saveHTML($item);
	}

	public function extractFromElement($element, $patterns, $multi = false)
	{
		$html = $this->getHtml($element);
		return $this->extractFromHtml($html, $patterns, $multi);
	}

	public function extractFromHtml($html, $patterns, $multi = false)
	{
		$patterns 	= is_array($patterns)? $patterns: [$patterns];
		$value 		= ($multi)? array(): '';
		if($html && sizeof($patterns) > 0) {
			foreach($patterns as $pkey => $pattern) {
				if($pkey == 0) {
					preg_match_all($pattern, $html, $matches);
					$value = ($multi)? (isset($matches[0])? $matches[0]: ''): (isset($matches[1][0])? $matches[1][0]: '');
				} else {
					if($multi) {
						foreach($value as $vkey => $val) {
							preg_match_all($pattern, $val, $matches);
							$value[$vkey] = (isset($matches[1][0]))? $matches[1][0]: '';
						}
					} else {
						preg_match_all($pattern, $value, $matches);
						$value = (isset($matches[1][0]))? $matches[1][0]: '';
					}
				}
			}
		}
		return ($multi)? array_filter($value): trim($value);
	}

	private function setMetaTags()
	{
		if(!$this->metaTags) {
			$this->metaTags = $this->findAll('html/head/meta');
		}
	}

	private function result()
	{
		$query = "//";
		if($this->targetElement) {
			$query .= $this->targetElement;
		}
		if($this->compareType) {
			if($this->compareType == 'contains') {
				$query .= "[contains(@".$this->targetAttr.", '".$this->targetAttrVal."')]";
			} else if($this->compareType == 'equal') {
				$query .= "[@".$this->targetAttr."='".$this->targetAttrVal."']";
			}	
		}
		if($this->subElements) {
			$query .= $this->subElements;
		}
		$this->reset();
		return $this->finder->query($query);
	}

	private function reset()
	{
		$this->targetElement 	= '';
		$this->compareType 		= '';
		$this->targetAttr 		= '';
		$this->targetAttrVal 	= '';
		$this->subElements 		= '';
	}
}