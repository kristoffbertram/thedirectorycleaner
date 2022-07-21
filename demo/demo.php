<?php
require __DIR__."/../src/TheDirectoryCleaner.php";
$thedirectorycleaner = new kristoffbertram\TheDirectoryCleaner\TheDirectoryCleaner();

// Set to true to capture all actions in a log.
$thedirectorycleaner->debug = true;

// Specify one or more directories.
$thedirectorycleaner->addDirectory(__DIR__ . '/cache/');
$thedirectorycleaner->addDirectory(__DIR__ . '/another-cache/');

// Specify ignorable files...
// either as full path...
$thedirectorycleaner->ignore(__DIR__ . '/cache/ignore-file.txt');

// or broad.
$thedirectorycleaner->ignore('ignore-me-too-file.txt');
$thedirectorycleaner->ignore('now.txt');

// Only applicable to files that are at least 2 hours old.
$thedirectorycleaner->after('2 hours');

// Clean / Empty.
$thedirectorycleaner->clean();

// Print a log.
$thedirectorycleaner->logs(true);