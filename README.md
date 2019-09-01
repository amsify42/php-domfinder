## PHP DOM Finder
PHP package for searching document object model efficiently and with more readable way.

### Installation

```txt
composer require amsify42/php-domfinder
```

## Table of Contents
1. [Loading Source](#1-loading-source)
2. [Meta Tags](#2-meta-tags)
3. [Elements](#3-elements)
4. [Element Class](#4-element-class)
5. [Element Id](#5-element-id)
6. [Element Attribute](#6-element-attribute)
7. [Regex Extraction](#7-regex-extraction)
8. [Element Methods](#8-element-methods)
9. [Multi Level Finder](#9-multi-level-finder)

### 1. Loading Source
---
#### File
```php
$domFinder 	= new Amsify42\DOMFinder\DOMFinder('path/to/file.html');
// or
$domFinder 	= new Amsify42\DOMFinder\DOMFinder();
$domFinder->load('path/to/file.html');
```

#### HTML
```php
$domFinder 	= new Amsify42\DOMFinder\DOMFinder('path/to/file.html', 'html');
// or
$domFinder 	= new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadHTML('path/to/file.html');
```

#### XML
```php
$domFinder 	= new Amsify42\DOMFinder\DOMFinder('path/to/file.xml', 'xml');
// or
$domFinder 	= new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadXML('path/to/file.xml');
```

#### URL
For HTML
```php
$domFinder 	= new Amsify42\DOMFinder\DOMFinder('http://www.site.com/file.html', 'html', true);
// or
$domFinder 	= new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadHTML('http://www.site.com/file.html', true);
```
For XML
```php
$domFinder 	= new Amsify42\DOMFinder\DOMFinder('http://www.site.com/file.xml', 'xml', true);
// or
$domFinder 	= new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadXML('http://www.site.com/file.xml', true);
```

#### Using helper method
```php
$domFinder = get_dom_finder('http://www.site.com/file.html', 'html', true);
```

**Note:** Make sure you pass `true` as 3rd parameter to constructor/helper method or 2nd parameter to load method for loading content from URL.

### Important Notes
#### 1. DOMDocument
`Amsify42\DOMFinder\DOMFinder` class uses `Amsify42\DOMFinder\DOM\Document` which extends PHP pre defined class `DOMDocument`. You can use all the methods of `DOMDocument` using this instance
```php
$domFinder->dom();	
```
Example:
```php
$domFinder->dom()->getElementsByTagName('p');	
```
#### 2. DomXPath
`Amsify42\DOMFinder\DOMFinder` class uses PHP pre defined class `DomXPath` for querying document. If you want to use all the methods of `DomXPath`, you can use this instance
```php
$domFinder->finder();
```
Example:
```php
$domFinder->finder()->query("/div[@class='body-entry']");	
```
#### 3. DOMElement
All the element results you get after querying document will be of type `Amsify42\DOMFinder\DOM\Element` which extends PHP pre defined class `DOMElement`.
```php
$anchors = $domFinder->find('a')->byClass('action-link')->all();
if($anchors->length)
{
	foreach($anchors as $anchor)
	{
		var_dump($anchor); // Will be of type Amsify42\DOMFinder\DOM\Element which extends DOMElement
	}
}
```
You can use all the methods of `DOMElement` from all the element items.
Example:
```php
foreach($anchors as $anchor)
{
	$anchor->getAttribute('href');
}
```
Most importantly, whenever you try to get the first or particular key element by index, it will either return `NULL` or element of type `Amsify42\DOMFinder\DOM\Element`.
Examples:
```php
$para = $domFinder->getFirstElement('p');
// or
$para = $domFinder->getElement('p', 1);
// or
$para = $domFinder->findFirst('p');
// or
$para = $domFinder->find('p')->first();
// or
$para = $domFinder->find('p')->get(1);
```

### 2. Meta Tags
---
After source has been loaded, you can use these meta tags related methods.
```php
$metaTags = $domFinder->metaTags();
```
To get specific meta tag value
```html
<meta name="title" content="Amsify42">
```
```php
$title = $domFinder->getMetaValue('name', 'title');
```
By default it takes **content** attribute value from meta element, to get value from other attribute, pass 3rd parameter
```html
<meta name="title" myattr="Amsify42">
```
```php
$title = $domFinder->getMetaValue('name', 'title', 'myattr');
```

### 3. Elements
---
To get specific elements from DOM
```php
$paras = $domFinder->getElements('p');
```
To get first element
```php
$para = $domFinder->getFirstElement('p');
```
To get the element by index position
```php
$para = $domFinder->getElement('p', 1);
```

### 4. Element Class
---
#### Equals
Find all elements by class name
```php
$elements = $domFinder->findByClass('section-items')->all();
```
Find first element by class
```php
$element = $domFinder->findByClass('section-items')->first();
// or
$element = $domFinder->findFirstByClass('section-items');
```
Find all div tag element by class
```php
$elements = $domFinder->find('div')->byClass('section-items')->all();
```
Find first div tag element by class
```php
$element = $domFinder->find('div')->byClass('section-items')->first();
```
For getting element by its key position
```php
$element = $domFinder->find('div')->byClass('section-items')->get(1); // This will return 2nd element
```

#### Like
Find all elements contains class
```php
$elements = $domFinder->findClassLike('section-items')->all();
```
Find first element contains class
```php
$element = $domFinder->findClassLike('section-items')->first();
// or
$element = $domFinder->findFirstClassLike('section-items');
```
Find all div tag element contains class
```php
$divs = $domFinder->find('div')->classLike('section-items')->all();
```
Find first div tag element contains class
```php
$div = $domFinder->find('div')->classLike('section-items')->first();
```
For getting element by its key position
```php
$div = $domFinder->find('div')->classLike('section-items')->get(1); // This will return 2nd element
```

### 5. Element Id
---
Find all elements by id
```php
$elements = $domFinder->findById('body-entry')->all();
```
Find first element by id
```php
$element = $domFinder->findById('body-entry')->first();
// or
$element = $domFinder->findFirstById('body-entry');
```
Find all div tag element by id
```php
$divs = $domFinder->find('div')->byId('body-entry')->all();
```
Find first div tag element by id
```php
$div = $domFinder->find('div')->byId('body-entry')->first();
```

### 6. Element Attribute
---
Find all elements by attribute
```php
$elements = $domFinder->findByAttr('data-section', 'paragraph')->all();
```
Find first element by attribute
```php
$element = $domFinder->findByAttr('data-section', 'paragraph')->first();
// or
$element = $domFinder->findFirstByAttr('data-section', 'paragraph');
```
Find all div tag element by attribute
```php
$divs = $domFinder->find('div')->byAttr('data-section', 'paragraph')->all();
```
Find first div tag element by attribute
```php
$div = $domFinder->find('div')->byAttr('data-section', 'paragraph')->first();
```
For getting element by its key position
```php
$div = $domFinder->find('div')->byAttr('data-section', 'paragraph')->get(1); // This will return 2nd element
```

### 7. Regex Extraction
---
To extract particular item from html, consider this sample html
```php
$html = '<div class="section">
			<script>var data={"name": "my name", "id":12345};</script>
		</div>';
$domFinder = new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadHTML($html);

$section = $domFinder->findFirstByClass('section');
if($section)
{
	$data = $section->extractByRegex("/data\=(.*?)\;</"); // Here you will get js dictionary data
}
```
For extracting multiple instances of data by regex, pass 2nd parameter as true
```php
$html = '<div class="section">
			<some-element class="some-class">{"name": "name one", "id":1}</some-element>
			<some-element class="some-class">{"name": "name two", "id":2}</some-element>
			<some-element class="some-class">{"name": "name three", "id":3}</some-element>
		</div>';
$domFinder = new Amsify42\DOMFinder\DOMFinder();
$domFinder->loadHTML($html);

$section = $domFinder->findFirstByClass('section');
if($section)
{
	$data = $section->extractByRegex("/class=\"some-class\">(.*?)\<\//", true); // Here you will get multiple js dictionary data as array
}
```
You can also pass multiple regex as array for multi level check and extraction
```php
$data = $section->extractByRegex(["/<some-element(.*?)some-element>/", "/class=\"some-class\">(.*?)\<\//"], true);
```

### 8. Element methods
---
These are the methods you can use at element level
```html
<ul class="list-items">
	<li>Item one</li>
	<li>Item two</li>
	<li>Item three</li>
</ul>	
```
```php
$ul = $domFinder->getElement('ul');
// or
$ul = $domFinder->findFirst('ul');
```
For getting outer and inner HTML of element, you can use these methods
```php
echo $ul->outerHTML();
```
Outer html will print
```txt
<ul class="list-items">
	<li>Item one</li>
	<li>Item two</li>
	<li>Item three</li>
</ul>
```
```php
echo $ul->innerHTML();
```
Inner html will print
```txt
<li>Item one</li>
<li>Item two</li>
<li>Item three</li>
```

### 9. Multi Level Finder
---
This section is to demonstrate how the dom finder works at multi level.
```html
<div class="parent-class">
	<div class="child-class">
		<ul class="list">
			<li class="item">one</li>
			<li class="item">two</li>
			<li class="item">three</li>
		</ul>
	</div>
	<div class="child-class">
		<ul class="list">
			<li class="item">one</li>
			<li class="item">two</li>
			<li class="item">three</li>
		</ul>
	</div>
</div>
```
#### Simple
```php
$uls = $domFinder->find('div')->byClass('child-class')->find('ul')->all();
// or
$uls = $domFinder->find('div')->byClass('child-class')->findAll('ul');
```
The above query is same as `DomXPath`
```php
$uls = $domFinder->finder()->query("/div[@class='child-class']/ul");
```
You will get all the **ul** elements
```php
if($uls->length)
{
	foreach($uls as $ul)
	{
		var_dump($ul);
	}
}
```

#### Element Level
This approach actually creates `DOMFinder` instance at each element level when you try to do query.
```php
$div = $domFinder->find('div')->byClass('parent-class')->first();
if($div)
{
	$divs = $div->find('div')->byClass('child-class')->all(); // At this level DOMFinder instance will be created and assigned to this element
	if($divs->length)
	{
		echo $divs->length;
	}
}
```