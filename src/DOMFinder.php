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

	/**
	 * Set Headers
	 * @param array $headers
	 */
	public function setHeaders($headers = [])
	{
		$this->headers = $headers;
	}

	/**
	 * Load related methods
	 */
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

	/**
	 * dom
	 * @return Amsify42\DOMFinder\DOM\Document
	 */
	public function dom()
	{
		return $this->dom;
	}

	/**
	 * finder
	 * @return DomXPath
	 */
	public function finder()
	{
		return $this->finder;
	}

	/**
	 * Meta tags related methods
	 */
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

	private function setMetaTags()
	{
		if(!$this->metaTags)
		{
			$this->metaTags = $this->findAll('html/head/meta');
		}
	}

	/**
	 * Element related methods
	 */
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

	/**
	 * General find through DOMXPath
	 */
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

	public function findAll($element = '*')
	{
		return $this->find($element)->all();
	}

	public function findFirst($element = '*')
	{
		return $this->find($element)->first();
	}


	/**
	 * Id related methods
	 */
	public function byId($value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= 'id';
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

	public function findById($id)
	{
		return $this->find()->byId($id);
	}

	public function findFirstById($id)
	{
		return $this->findById($id)->first();
	}

	public function findIdLike($id)
	{
		return $this->find()->idLike($id);
	}

	public function findFirstIdLike($id)
	{
		return $this->findIdLike($id)->first();
	}


	/**
	 * Class related methods
	 */
	public function byClass($value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= 'class';
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

	public function findByClass($class)
	{
		return $this->find()->byClass($class);
	}

	public function findFirstByClass($class)
	{
		return $this->findByClass($class)->first();
	}

	public function findClassLike($class)
	{
		return $this->find()->classLike($class);
	}

	public function findFirstClassLike($class)
	{
		return $this->findClassLike($class)->first();
	}


	/**
	 * Attribute related methods
	 */
	public function byAttr($attr, $value)
	{
		$this->compareType 		= 'equal';
		$this->targetAttr 		= $attr;
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function attrLike($attr, $value)
	{
		$this->compareType 		= 'contains';
		$this->targetAttr 		= $attr;
		$this->targetAttrVal 	= $value;
		return $this;
	}

	public function findByAttr($attr, $value)
	{
		return $this->find()->byAttr($attr, $value);
	}

	public function findFirstByAttr($attr, $value)
	{
		return $this->findByAttr($attr, $value)->first();
	}

	public function findAttrLike($attr, $value)
	{ 
		return $this->find()->attrLike($attr, $value);
	}

	public function findFirstAttrLike($attr, $value)
	{
		return $this->findAttrLike($attr, $value)->first();
	}

	/**
	 * Html related methods
	 */
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

	/**
	 * Result of DOMXPath
	 * @return Array of \Amsify42\DOMFinder\DOM\Element
	 */
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

	/**
	 * Reset the XPath after each result
	 * @return void
	 */
	private function reset()
	{
		$this->targetElement 	= '';
		$this->compareType 		= '';
		$this->targetAttr 		= '';
		$this->targetAttrVal 	= '';
		$this->subElements 		= '';
	}
}