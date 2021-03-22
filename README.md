# thedirectorycleaner

$TheDirectoryCleaner->directory(__DIR__."/cache");

$TheDirectoryCleaner->after("1 day");

$TheDirectoryCleaner->clean();