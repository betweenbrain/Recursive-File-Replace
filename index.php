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


/**
 * Create first char grouped array of source files
 */

$source = array();
foreach (array_filter(glob('*'), 'is_file') as $file)
{
	echo "$file size " . filesize($file) . "\n";
	$source[substr($file, 0, 1)][$file] = __DIR__ . '/' . $file;

}

// echo print_r($source, true);

$path = __DIR__ . '/foo';

/**
 * Get all files in target dir
 */
$time_start = microtime(true);
$i = null;
$files = null;
$dirs = null;
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
foreach ($objects as $object)
{
	$i++;
	if (!$object->isDir())
	{
		$files++;

		/**
		 * Check for match
		 */
		$filename = $object->getFileName();
		if (array_key_exists($filename, $source[substr($filename, 0, 1)]))
		{
			$path = str_replace($filename, '', $object->getPathName());

			// Backup old file
			rename($path . $filename, $path . $filename . '.bak');

			// Copy
			if (chunked_copy($source[substr($filename, 0, 1)][$filename], $path . $filename) === filesize($source[substr($filename, 0, 1)][$filename]))
			{

				unlink($path . $filename . '.bak');
			}
		}
	}
	else
	{
		$dirs++;
	}
}
$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Inspected $i objects, $files files and $dirs dirs, in $time seconds\n";

// http://stackoverflow.com/a/6564818/901680
function chunked_copy($from, $to)
{
	# 1 meg at a time, you can adjust this.
	$buffer_size = 1048576;
	$ret         = 0;
	$fin         = fopen($from, "rb");
	$fout        = fopen($to, "w");
	while (!feof($fin))
	{
		$ret += fwrite($fout, fread($fin, $buffer_size));
		echo $ret . "\n";
	}
	fclose($fin);
	fclose($fout);

	return $ret; # return number of bytes written
}

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
