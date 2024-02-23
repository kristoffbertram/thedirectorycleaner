<?php
require __DIR__."/../src/TheDirectoryCleaner.php";
$thedirectorycleaner = new kristoffbertram\TheDirectoryCleaner\TheDirectoryCleaner();

// Set to true to capture all actions in a log.
// Debugging will NOT remove any resources.
$thedirectorycleaner->debug = true;

// Specify one or more directories.
$thedirectorycleaner->addDirectory(__DIR__ . '/cache/');
$thedirectorycleaner->addDirectory(__DIR__ . '/another-cache/');

// Specify ignorable files...
// either as full path...
$thedirectorycleaner->ignore(__DIR__ . '/cache/ignore-me-for-sure.txt');

// or broad...
$thedirectorycleaner->ignore('*.jpg');

// or very broad...
$thedirectorycleaner->ignore('important');

// For demo purposes only: touching 'now', so it's always fresh.
touch(__DIR__ . '/cache/now.txt');

// Only applicable to files that are at least 60 seconds old.
// Refresh this demo in a minute to determine the difference.
$thedirectorycleaner->after('60 seconds');

// Clean / Empty.
$thedirectorycleaner->clean();

// Print a log.
$thedirectorycleaner->logs(true);