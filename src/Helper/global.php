<?php


function get_dom_finder($source, $type='file', $loadContent=false)
{
	return new \Amsify42\DOMFinder\DOMFinder($source, $type, $loadContent);
}