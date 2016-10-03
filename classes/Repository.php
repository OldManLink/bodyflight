<?php
require_once('Bucket.php');

class Repository extends PersistentObject
{
    private $bucketNames = array();
    private $lastFlight = 0;

    public function Repository()
    {
        $this->readFromStorage();
    }

    public function addFlights($newFlights)
    {
        $this->makeDirty();
        $this->saveToStorage();
    }

    /**
     * Protected methods
     */

    protected function storage()
    {
        return $this->storageRoot."repository.php";
    }

    protected function copyFrom($anotherRepo)
    {
        $this->bucketNames = $anotherRepo->bucketNames;
        $this->lastFlight = $anotherRepo->lastFlight;
    }

    /**
     * Private methods
     */
}
?>