<?php
require_once('PersistentObject.php');
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
        $tFlights = $newFlights;
        while(count($tFlights) > 0)
        {
            $tBucket = $this->getBucket($tFlights[0]);
            $tFlights = $tBucket->addFlights($tFlights);
            if($tBucket->isDirty()) $this->makeDirty();
        }
        $this->saveToStorage();
    }

    /**
     * Protected methods
     */

    protected function storage()
    {
        return $this->getStorageRoot()."repository.php";
    }

    protected function copyFrom($anotherRepo)
    {
        $this->bucketNames = $anotherRepo->bucketNames;
        $this->lastFlight = $anotherRepo->lastFlight;
    }

    /**
     * Private methods
     */
    private function getBucket($flight)
    {
        $tBucketName = Bucket::generateFileName($flight);
        if(!in_array($tBucketName, $this->bucketNames))
        {
            $this->bucketNames[] = $tBucketName;
            $this->makeDirty();
        };
        return new Bucket($flight);
    }
}
?>