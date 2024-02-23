# thedirectorycleaner

_2.0.0_

## Introduction

 A PHP utility class for cleaning up directories by deleting files and folders that are (optionally) not ignored and are older than a specified age. This class supports ignoring specific directories, files or patterns.

## Usage

Specify one or more directories that require cleaning.  
Optionally, define file paths, -names or patterns to be ignored or set a time (e.g. 10 minutes, 1 day, 2 weeks.)  
Finally, run clean();

```
$TheDirectoryCleaner->addDirectory(__DIR__."/cache");
$TheDirectoryCleaner->ignore(__DIR__."/cache/ignore.txt");
$TheDirectoryCleaner->ignore("*.jpg");
$TheDirectoryCleaner->after("1 day"); // Accepts any textual datetime
$TheDirectoryCleaner->clean();
```

### Demo

Open ~/demo in your terminal and run ``php -S localhost:8000``.

## Changelog

- ``directory()`` was renamed to ``addDirectory()``.
- Introduced patterns.
- ``debug`` no longer cleans. Best used together with ``logs()``.


## Disclaimer
- Built for personal use, but I imagine you may have a need for it too. 
-  Be aware, this package irreversibly deletes files and folders. Use at your own risk.  
**I take absolutely no responsibility for any unexpected loss of data.**

