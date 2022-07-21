# thedirectorycleaner

This package will delete directories and files inside a directory. Either instant, or after a specified time.

## Usage

Specify one more directories that need cleaning. Optionally, define filepaths or file names to be ignored or set a time (e.g. 10 minutes, 1 day, 2 weeks.) Finally, run clean();

$TheDirectoryCleaner->addDirectory(__DIR__."/cache");

$TheDirectoryCleaner->ignore(__DIR__."/cache/ignore.txt");

$TheDirectoryCleaner->ignore("anything.txt");

$TheDirectoryCleaner->after("1 day");

$TheDirectoryCleaner->clean();

### Changelog

- ``directory()`` was renamed to ``addDirectory()``.

## Disclaimer
- Largely untested at this time.
- Built for personal use, but I imagine you may have a need for it too. 
-  Be aware, this package irreversibly deletes files and folders. Use at your own risk. I take absolutely no responsibility for any unexpected loss of data.

