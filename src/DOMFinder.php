<?php

namespace Amsify42\DOMFinder;

use Amsify42\DOMFinder\DOM\Document;
use Amsify42\DOMFinder\Helper\Html;
use DomXPath;


class DOMFinder
{
	private $dom, $finder;

	private $headers 		= [];
	private $loaded 		= false;

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
		return $this->loadByType($source);
	}

	public function loadHTML($html, $loadContent=false)
	{
		return $this->loadByType($html, 'html', $loadContent);
	}

	public function loadXML($xml, $loadContent=false)
	{
		return $this->loadByType($xml, 'xml', $loadContent);
	}

	private function loadByType($source, $type='file', $loadContent=false)
	{
		$this->metaTags = NULL;
		$this->dom 		= new Document;
		$this->dom->registerNodeClass('DOMElement', \Amsify42\DOMFinder\DOM\Element::class);
		if($type == 'html')
		{
			if($loadContent)
			{
				$content = $this->getURLContents($source);
				if($content)
				{
					$this->loaded = true;
					$this->dom->loadHTML($content);
				}
			}
			else
			{
				$this->loaded = true;
				$this->dom->loadHTML($source);	
			}
		}
		else if($type == 'xml')
		{
			if($loadContent)
			{
				$content = $this->getURLContents($source);
				if($content)
				{
					$this->loaded = true;
					$this->dom->loadXML($content);
				}
			}
			else
			{
				$this->loaded = true;
				$this->dom->loadXML($source);	
			}
		}
		else
		{
			if(filter_var($source, FILTER_VALIDATE_URL))
			{
				if($this->getURLType($source) == 'xml')
				{
					$content = $this->getURLContents($source);
					if($content)
					{
						$this->loaded = true;
						$this->dom->loadXML($content);
					}
				}
				else
				{
					$content = $this->getURLContents($source);
					if($content)
					{
						$this->loaded = true;
						$this->dom->loadHTML($content);
					}
				}
			}
			else
			{
				if(is_file($source))
				{
					$this->loaded = true;
					$this->dom->load($source);
				}
			}
		}
		$this->finder = new DomXPath($this->dom);
		return $this->loaded;
	}

	public function isLoaded()
	{
		return $loaded;
	}

	private function getURLContents($url)
	{
		$headers = $this->createHeaders();
		if($headers)
		{
			$options = [
				'http' => [
					'method' => 'GET',
					'header' => $headers
				]
			];
			$context = stream_context_create($options);
			return file_get_contents($url, false, $context);
		}
		else
		{
			return file_get_contents($url);	
		}
	}

	private function createHeaders()
	{
		if(sizeof($this->headers)> 0)
		{
			return implode('\r\n', $this->headers);
		}
		return NULL;
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
		if($result->length)
		{
			return $result->item(0);
		}
		return NULL;
	}

	public function getElement($tag='*', $index=0)
	{
		$result = $this->dom->getElementsByTagName($tag);
		if($result->length)
		{
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
		if($this->metaTags && $this->metaTags->length)
		{
			foreach($this->metaTags as $metaTag)
			{
				if($metaTag->getAttribute($attr) == $key)
				{
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

	public function findFirstByClass($class)
	{
		return $this->findByClass($class)->first();
	}

	public function findFirstClassLike($class)
	{
		return $this->findClassLike($class)->first();
	}

	public function findFirstById($id)
	{
		return $this->findById($id)->first();
	}

	public function findFirstByAttr($type, $value)
	{
		return $this->findByAttr($type, $value)->first();
	}

	public function find($element = '*')
	{
		if($this->targetElement)
		{
			$this->subElements .= '/'.$element;
		}
		else
		{
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
		if($result->length)
		{
			return $result->item(0);
		}
		return NULL;
	}

	public function get($index=0)
	{
		$result = $this->result();
		if($result->length)
		{
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
		return Html::extractByRegex($html, $patterns, $multi);
	}

	public function extractFromHtml($html, $patterns, $multi = false)
	{
		return Html::extractByRegex($html, $patterns, $multi);
	}

	private function setMetaTags()
	{
		if(!$this->metaTags)
		{
			$this->metaTags = $this->findAll('html/head/meta');
		}
	}

	private function result()
	{
		$query = "//";
		if($this->targetElement)
		{
			$query .= $this->targetElement;
		}
		if($this->compareType)
		{
			if($this->compareType == 'contains')
			{
				$query .= "[contains(@".$this->targetAttr.", '".$this->targetAttrVal."')]";
			}
			else if($this->compareType == 'equal')
			{
				$query .= "[@".$this->targetAttr."='".$this->targetAttrVal."']";
			}	
		}
		if($this->subElements)
		{
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