<?php

namespace Amsify42\Tests;

use PHPUnit\Framework\TestCase;
use Amsify42\DOMFinder\DOMFinder;

final class RegexTest extends TestCase
{
	public function testRegex()
	{
		$this->single();
		$this->multiple();
	}

	private function single()
	{
		$html = '<div class="section">
					<script>var data={"name": "my name", "id":12345};</script>
				</div>';
		$domFinder = new DOMFinder();
		$domFinder->loadHTML($html);

		$section = $domFinder->findFirstByClass('section');
		if($section)
		{
			// Here you will js dictionary data
			$this->assertJsonStringEqualsJsonString('{"name": "my name", "id":12345}', $section->extractByRegex("/data\=(.*?)\;</"));
		}
	}

	private function multiple()
	{
		libxml_use_internal_errors(true);
		$html = '<div class="section">
					<some-element class="some-class">{"name": "name one", "id":1}</some-element>
					<some-element class="some-class">{"name": "name two", "id":2}</some-element>
					<some-element class="some-class">{"name": "name three", "id":3}</some-element>
				</div>';
		$domFinder = new DOMFinder();
		$domFinder->loadHTML($html);

		$section = $domFinder->findFirstByClass('section');
		if($section)
		{
			// Here you will multiple js dictionary data as array
			$jsons = $section->extractByRegex("/class=\"some-class\">(.*?)\<\//", true);
			foreach($jsons as $json)
			{
				$this->assertArrayHasKey('name', json_decode($json, true));
			}
		}
	}
}