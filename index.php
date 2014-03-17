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
 * Create array of source files grouped by first character
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
