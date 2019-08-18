## PHP DOM Finder
PHP package for searching document object model efficiently and with more readable way.

### Summary
This package extends PHP pre defined classes DOMDocument & DOMElement and use DomXPath for quering document.

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
6. [Element Attribute](#6-element-id)
7. [Regex Extraction](#7-regex-extraction)
8. [Element Helpers](#8-element-helpers)
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
**Note:** Make sure you pass *true* to 3rd parameter of constructor or 2nd parameter of method for loading content from URL.

### 2. Meta Tags
---
After source has been loaded you use these meta tags related methods.
```php
$metaTags = $domFinder->metaTags();
```
To get specific meta tag value
```php
$image = $domFinder->getMetaValue('name', 'title');
```
By default it takes **content** attribute value from meta, to get value from other attribute, pass 3rd parameter
```php
$image = $domFinder->getMetaValue('name', 'title', 'content');
```

### 3. Elements
---
To get specific elements from DOM
```php
$paras = $domFinder->getElements('p');
```
To get first element
```php
$paras = $domFinder->getFirstElement('p');
```
To get the element by index position
```php
$paras = $domFinder->getElement('p', 1);
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
if($section) {
	// Here you will get js dictionary data
	$data = $section->extractByRegex("/data\=(.*?)\;</");
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
if($section) {
	// Here you will multiple js dictionary data as array
	$data = $section->extractByRegex("/class=\"some-class\">(.*?)\<\//", true);
	var_dump($data);
}
```
You can also pass multiple regex as array for multi level check
```php
$data = $section->extractByRegex([
								"/<some-element(.*?)some-element>/",
								"/class=\"some-class\">(.*?)\<\//"
							], true);	
```

### 8. Regex Extraction
---
These are the helper methods you can use at element level
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

```php
$div = $domFinder->find('div')->byClass('parent-class')->first();
if($div) {
	$divs = $div->find('div')->byClass('child-class')->all();
	if($divs->length) {
		echo $divs->length;
	}
}
```
**Important Note**: Whenever method is used to get first or particular index element, it will either return *NULL* or single element instance.