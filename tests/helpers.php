<?php

function getSample($filename)
{
	$filePath = __DIR__.'/samples/'.$filename;
	if(is_file($filePath)) {
		return file_get_contents($filePath);
	}
	return NULL;
}