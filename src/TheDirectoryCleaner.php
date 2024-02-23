<?php
namespace kristoffbertram\TheDirectoryCleaner;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

/**
 * Class TheDirectoryCleaner
 *
 * A utility class for cleaning up directories by deleting files and folders
 * that are (optionally) not ignored and are older than a specified age.
 * This class supports ignoring specific directories, files or patterns,
 * including partial matches.
 *
 * @package kristoffbertram\TheDirectoryCleaner
 * @author Kristoff Bertram
 * @version 2.0.0
 */
class TheDirectoryCleaner
{
    protected $resources = [];
    protected $ignores = [];
    public $debug = false;
    public $logs = [];

    private function addResource($directory)
    {
        $this->resources[] = [$directory, 0];
        if ($this->debug) {
            $this->addLog($directory . " to be cleaned.");
        }
    }

    private function addIgnore($resource)
    {
        $this->ignores[] = '*' . $resource . '*';
    }

    private function addLog($log)
    {
        $this->logs[] = $log;
    }

    private function getResources()
    {
        return $this->resources;
    }

    private function getIgnores()
    {
        return $this->ignores;
    }

    private function deleteResource($r)
    {
        if (!$this->debug) {
            if ($r->isDir()) {
                rmdir($r->getPathname());
            } else {
                unlink($r->getPathname());
            }
        }
    }

    private function isIgnored($filePath)
    {
        $fileName = basename($filePath);
        foreach ($this->getIgnores() as $ignorePattern) {
            // Check against both the full path and just the filename.
            if (fnmatch($ignorePattern, $filePath) || fnmatch($ignorePattern, $fileName)) {
                return true;
            }
        }
        return false;
    }

    private function doCleaning()
    {
        foreach ($this->getResources() as $resource) {
            $directory = $resource[0];
            $canBeDeletedAfter = $resource[1];

            if (is_dir($directory)) {
                $di = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

                foreach ($ri as $r) {
                    $filePath = $r->getPathName();
                    if (!$this->isIgnored($filePath)) {
                        $creationDate = filemtime($r);
                        $timeToDelete = $creationDate + $canBeDeletedAfter;

                        if (time() >= $timeToDelete) {
                            $this->deleteResource($r);
                            $this->addLog($filePath . ($this->debug ? " would be" : "was") . " deleted.");
                        } else if ($this->debug) {
                            $this->addLog($filePath . " would not be deleted because it is not old enough.");
                        }
                    } else if ($this->debug) {
                        $this->addLog($filePath . " would not be deleted because it is to be ignored.");
                    }
                }
            }
        }
    }

    public function addDirectory($directory)
    {
        $this->addResource($directory);
        return $this;
    }

    public function ignore($file)
    {
        $this->addIgnore($file);
        return $this;
    }

    public function after($string)
    {
        $now = new \DateTime();
        $then = new \DateTime('now +' . $string);
        $canBeDeletedAfter = $then->getTimestamp() - $now->getTimestamp();

        foreach ($this->getResources() as $index => $resource) {
            if ($resource[1] == 0) {
                $this->resources[$index][1] = $canBeDeletedAfter;
            }
        }
        return $this;
    }

    public function clean()
    {
        $this->doCleaning();
        return $this;
    }

    public function logs($echo = true)
    {
        if ($echo) {
            echo '<pre>';
            print_r($this->logs);
            echo '</pre>';
        } else {
            return $this->logs;
        }
    }
}