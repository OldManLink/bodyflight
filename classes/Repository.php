<?php
require_once('PersistentObject.php');
require_once('Bucket.php');

class Repository extends PersistentObject
{
    private $bucketNames = array();
    private $lastFlight = 0;
    private $latestCheck = 0;

    public function Repository()
    {
        $this->readFromStorage();
    }

    public function addFlights($newFlights)
    {
        print("Previous last flight: ".date("Y-m-d H:i:s", $this->lastFlight)."\n");
        printf("Received %d flights\n", count($newFlights));
        $tFlights = $newFlights;
        while(count($tFlights) > 0)
        {
            $tBucket = $this->getBucket($tFlights[0]);
            $tFlights = $tBucket->addFlights($tFlights);
            if(0 == count($tFlights))
            {
                $this->lastFlight = $tBucket->getLastFlight();
                $this->makeDirty();
            };
        }
        $this->saveToStorage();
    }

    public function getLastFlight() { return $this->lastFlight; }
    public function getLatestCheck() { return $this->latestCheck; }
    public function setLatestCheck($now) { $this->latestCheck = $now; }

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
        $this->latestCheck = $anotherRepo->latestCheck;
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