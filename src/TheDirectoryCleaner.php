<?php
namespace kristoffbertram\TheDirectoryCleaner;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

class TheDirectoryCleaner
{

    protected $resources = array();
    protected $ignores = array();

    public $debug = false;
    public $logs = array();

    private function addResource($directory)
    {
        $this->resources[] = array($directory , 0);

        if (true === $this->debug) {

            if (is_dir($directory)) {

                $this->addLog($directory. " to be cleaned.");

            } else {

                $this->addLog($directory. " does not exist.");

            }

        }

    }

    private function addIgnore($resource)
    {
        $this->ignores[] = $resource;
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

    private function doCleaning() {

        if ($this->getResources()) {

            foreach ($this->getResources() as $index => $resource) {

                $directory = $resource[0];
                $canBeDeletedAfter = $resource[1];

                if (is_dir($directory)) {

                    $di = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
                    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

                    foreach ($ri as $r) {

                        if ( ! (
                            (in_array($r, $this->getIgnores())) ||
                            (in_array($r->getPathName(), $this->getIgnores())) ||
                            (in_array($r->getFileName(), $this->getIgnores()))
                        ) ) {

                            $creationDate = filemtime($r);
                            $timeToDelete = $creationDate + $canBeDeletedAfter;

                            if (time() >= $timeToDelete) {

                                if ($r->isDir()) {

                                    rmdir($r);

                                } else {

                                    unlink($r);

                                }

                                $this->addLog($r->getPathName(). " was deleted.");

                            } else {

                                if (true === $this->debug) {

                                    $this->addLog($r->getPathName(). " was not deleted because it is not old enough.");

                                }

                            }

                        } else {

                            if (true === $this->debug) {

                                $this->addLog($r->getPathName(). " was not deleted because it is to be ignored.");

                            }

                        }

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

    /**
     * Ignore either a full path or (broad) filename.
     *
     * @param $file
     * @return $this
     *
     */

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

        if (true === $echo) {

            echo '<pre>';
            print_r($this->logs);
            echo '</pre>';

        } else {

            return $this->logs;

        }

    }

}