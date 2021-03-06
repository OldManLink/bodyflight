<?php
require_once('PersistentObject.php');

class Bucket extends PersistentObject
{
	private $flights = array();
	private $lastFlight = 0;
	private $fileName;
	
    public static function generateFileName($flight)
    {
        return date("Ym", $flight);
    }

    public function Bucket($flight)
    {
        $this->initializeFileName($flight);
        $this->readFromStorage();
    }

    public function addFlights($newFlights)
    {
		foreach (array_filter($newFlights, array($this, "shouldContain")) as $flight)
    	{
        	if($flight > $this->lastFlight)
        	{
            	$this->flights[] = $flight;
            	$this->lastFlight = $flight;
                $this->makeDirty();
            	print(date("Y-m-d H:i:s", $flight)." added\n");
        	} else
        	{
            	print(date("Y-m-d H:i:s", $flight)." skipped\n");
        	};
    	};
        unset($flight); // break the reference with the last element
        $this->saveToStorage();

        return array_values(array_filter($newFlights, array($this, "shouldReject")));
    }

	public function getFlights()
    {
        return $this->flights;
    }

	public function getLastFlight()
    {
        return $this->lastFlight;
    }

	public function getFileName()
    {
        return $this->fileName;
    }

    public function flightCount()
    {
        return count($this->flights);
    }

    public function getPrevious()
    {
        $lastMonth = (0 + $this->fileName - 1) . "01";
        $lastMonthFlight = strtotime($lastMonth);
        $lastMonthBucket = new Bucket($lastMonthFlight);
        return ($lastMonthBucket->flightCount() > 0) ? $lastMonthBucket : null;
    }

    public function getPreviousFlight($pFlight)
    {
        $i = 0 + array_search($pFlight, $this->flights);
        return ($i > 0) ? $this->flights[$i - 1] : null;
    }

    /**
     * Protected methods
     */

    protected function storage()
    {
        return $this->getStorageRoot().$this->fileName.".php";
    }

    protected function copyFrom($anotherBucket)
    {
        $this->flights = $anotherBucket->flights;
        $this->lastFlight = $anotherBucket->lastFlight;
        $this->fileName = $anotherBucket->fileName;
    }
    
    /**
     * Private methods
     */

    private function initializeFileName($flight)
    {
        $this->fileName = $this->generateFileName($flight);
    }

    private function shouldContain($flight)
    {
        return strcmp($this->fileName, $this->generateFileName($flight)) == 0;
    }

    private function shouldReject($flight)
    {
        return $flight > $this->lastFlight && !$this->shouldContain($flight);
    }
}
?>