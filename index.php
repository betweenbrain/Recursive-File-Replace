#!/usr/bin/php
<?php

/**
 * File       index.php
 * Created    3/7/14 4:16 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2014 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

$path = '/home/mthomas';

/**
 * Get all files in target dir
 */
$time_start = microtime(true);
$i = null;
$files = null;
$dirs = null;
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST );
foreach ($objects as $object)
{
	$i++;
	if (!$object->isDir())
	{
		//echo "Object: $object\n";
        $files++;
	}else
    {
    $dirs++;
    }
}
$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Inspected $i objects, $files files and $dirs dirs, in $time seconds\n";
/*
$iterator = new FilesystemIterator(dirname(__FILE__), FilesystemIterator::CURRENT_AS_PATHNAME);
foreach ($iterator as $fileinfo){
	$results[]=$fileinfo;
}
echo '<pre>Target: ' . print_r($results, true) . '</pre>';

/**
 * Build multidimensional array of files
 *
 * @param DirectoryIterator $it
 *
 * @return array
 */
/*
function DirectoryIteratorToArray(DirectoryIterator $it)
{
	$result = array();
	foreach ($it as $key => $child)
	{
		if ($child->isDot())
		{
			continue;
		}
		$name = $child->getBasename();
		if ($child->isDir())
		{
			$subit         = new DirectoryIterator($child->getPathname());
			$result[$name] = DirectoryIteratorToArray($subit);
		}
		else
		{
			$result[$name] = $child->getPathname();
		}
	}

	return $result;
}

$files = DirectoryIteratorToArray(new DirectoryIterator($path));
echo '<pre>Target: ' . print_r($files, true) . '</pre>';

/**
 * Recursive array key exists
 *
 * @param $needle
 * @param $haystack
 *
 * @return bool
 */
/*
function array_key_exists_r($needle, $haystack)
{
	$result = array_key_exists($needle, $haystack);
	if ($result)
	{
		return $result;
	}
	foreach ($haystack as $v)
	{
		if (is_array($v))
		{
			$result = array_key_exists_r($needle, $v);
		}
		if ($result)
		{
			return $result;
		}
	}

	return $result;
}

/**
 * Glob of file in current dir
 */
/*
$iterator = new FilesystemIterator(dirname(__FILE__), FilesystemIterator::CURRENT_AS_PATHNAME);

$result = array();
foreach ($iterator as $fileinfo)
{
	if (!$iterator->isDir())
	{
		if (array_key_exists_r($iterator->getBasename(), $files))
		{


		}

	}
}
*/
