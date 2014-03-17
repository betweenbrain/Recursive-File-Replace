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

// PHP CLI only
PHP_SAPI === 'cli' or die();

if ($argc < 4 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) : ?>

	This is a command line PHP script to recursivley replace files from those in the current directory.

	Usage: <?php echo $argv[0]; ?> [-t] '/home/foo/bar' [-Y|-N]

	-t   Full path of the directory to recursively search for files to replace.
	-Y   Perform file replacement.
	-N   Do not perform file replacement, but instead ouput results to 'results.log'. Default behavior if not designated.

	With the --help, -help, -h, or -? options, you can get this help.
<?php
	exit;
endif;

// Check that target isset and is a dir
if($argv[1] == "-t" && isset($argv[2])){
	if(!is_dir($argv[2])){
		echo "$argv[2] does not exist!\n";
		exit;
	}
}

/**
 * Create array of source files grouped by first character
 */

$source = array();
foreach (array_filter(glob('*'), 'is_file') as $file)
{
	echo "$file size " . filesize($file) . "\n";
	$source[substr($file, 0, 1)][$file] = __DIR__ . '/' . $file;

}


if($argv[1] == "-t" && isset($argv[2])){
	echo $argv[2];
}
$path = __DIR__ . '/foo';

/**
 * Get all files in target dir
 */

$time_start = microtime(true);
$i = null;
$files = null;
$dirs = null;
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($argv[2]), RecursiveIteratorIterator::SELF_FIRST);
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
