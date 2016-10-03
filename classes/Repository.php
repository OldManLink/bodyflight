<?php
require_once('Bucket.php');

class Repository
{
    private $bucketNames = array();
    private $lastFlight = 0;
    private $dirty = false;

    function Repository()
    {
        if ($this->isSaved()) $this->readFromStorage();
    }

    function addFlights($newFlights)
    {
        $this->makeDirty();
        $this->saveToStorage();
    }

    /**
     * Private methods
     */

    private function makeDirty()
    {
        $this->dirty = true;
    }

    private function makeClean()
    {
        $this->dirty = false;
    }

    private function isSaved()
    {
        return file_exists($this->storage());
    }

    private function saveToStorage()
    {
        if($this->dirty)
        {
            file_put_contents($this->storage(), serialize($this));
            $this->makeClean();
        }
    }

    private function readFromStorage()
    {
        $this->copyFrom(unserialize(file_get_contents($this->storage())));
        $this->makeClean();
    }

    private function storage()
    {
        return "../storage/repository.php";
    }

    private function copyFrom($anotherRepo)
    {
        $this->bucketNames = $anotherRepo->bucketNames;
        $this->lastFlight = $anotherRepo->lastFlight;
    }
}
?>