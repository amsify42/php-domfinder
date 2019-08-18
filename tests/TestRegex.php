<?php

namespace Amsify42\Tests;

use Amsify42\DOMFinder\DOMFinder;

class TestRegex
{
	public function process()
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
		if($section) {
			// Here you will js dictionary data
			$data = $section->extractByRegex("/data\=(.*?)\;</");
			var_dump($data);
		}
	}

	private function multiple()
	{
		$html = '<div class="section">
					<some-element class="some-class">{"name": "name one", "id":1}</some-element>
					<some-element class="some-class">{"name": "name two", "id":2}</some-element>
					<some-element class="some-class">{"name": "name three", "id":3}</some-element>
				</div>';
		$domFinder = new DOMFinder();
		$domFinder->loadHTML($html);

		$section = $domFinder->findFirstByClass('section');
		if($section) {
			// Here you will multiple js dictionary data as array
			$data = $section->extractByRegex("/class=\"some-class\">(.*?)\<\//", true);
			var_dump($data);
		}
	}
}