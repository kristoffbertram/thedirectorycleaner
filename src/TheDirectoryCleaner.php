<?php
namespace kristoffbertram\TheDirectoryCleaner;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

class TheDirectoryCleaner
{

    protected $resources = array();

    private function addResource($directory)
    {
        $this->resources[] = array($directory , 0);
    }

    private function getResources()
    {
        return $this->resources;
    }

    private function doCleaning() {

        foreach ($this->getResources() as $index => $resource) {

            $directory = $resource[0];
            $canBeDeletedAfter = $resource[1];

            if (is_dir($directory)) {

                $di = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

                foreach ($ri as $r) {

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

    public function directory($directory)
    {

        $this->addResource($directory);


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

    }

    public function clean()
    {

        $this->doCleaning();

    }

}