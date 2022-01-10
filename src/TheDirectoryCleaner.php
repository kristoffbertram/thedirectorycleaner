<?php
namespace kristoffbertram\TheDirectoryCleaner;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

class TheDirectoryCleaner
{

    protected $resources = array();
    protected $ignores = array();

    private function addResource($directory)
    {
        $this->resources[] = array($directory , 0);
    }

    private function addIgnore($resource)
    {
        $this->ignores[] = $resource;
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

                        if (!(in_array($r, $this->getIgnores()))) {

                            $creationDate = filemtime($r);
                            $timeToDelete = $creationDate + $canBeDeletedAfter;

                            if (time() >= $timeToDelete) {

                                if ($r->isDir()) {

                                    rmdir($r);

                                } else {

                                    unlink($r);

                                }

                            }

                        }

                    }

                }

            }

        }

    }

    public function directory($directory)
    {

        $this->addResource($directory);
        return $this;


    }

    public function ignore($resource)
    {

        $this->addIgnore($resource);
        return $this;

    }

    public function after($string)
    {

        $now = new \DateTime();
        $then = new \DateTime('now +'.$string);
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

}