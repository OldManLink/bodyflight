<?php
class Bucket
{
	private $flights = array();
	private $lastFlight = 0;
	private $fileName;
    private $dirty = false;
	
    function Bucket($flight)
    {
        $this->initializeFileName($flight);
        if ($this->isSaved()) $this->readFromStorage();
    }

    public static function generateFileName($flight)
    {
        return date("Ym", $flight);
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

    private function initializeFileName($flight)
    {
        $this->fileName = $this->generateFileName($flight);
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

    private function shouldContain($flight)
    {
        return strcmp ($this->fileName, $this->generateFileName($flight)) == 0;
    }

    private function shouldReject($flight)
    {
        return $flight > $this->lastFlight && !$this->shouldContain($flight);
    }

    private function storage()
    {
    	return "../storage/".$this->fileName.".php";
    }

    private function copyFrom($anotherBucket)
    {
    	$this->flights = $anotherBucket->flights;
    	$this->lastFlight = $anotherBucket->lastFlight;
    	$this->fileName = $anotherBucket->fileName;
    }
}
?>