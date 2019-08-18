<?php

namespace Amsify42\DOMFinder\Helper;

class Html
{
	public static function extractByRegex($html, $patterns, $multi = false)
	{
		$patterns 	= is_array($patterns)? $patterns: [$patterns];
		$value 		= ($multi)? array(): '';
		if($html && sizeof($patterns) > 0) {
			foreach($patterns as $pkey => $pattern) {
				if($pkey == 0) {
					preg_match_all($pattern, $html, $matches);
					$value = ($multi)? (isset($matches[1])? $matches[1]: ''): (isset($matches[1][0])? $matches[1][0]: '');
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
}