<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/Bucket.php');

class TestOfBucket extends UnitTestCase
{
    private $testFlight0 = "2012-05-31 23:59:59";
    private $testFlight1 = "2012-06-05 12:34:56";
    private $testFlight2 = "2012-07-01 00:00:00";
    private $fileName1 = "201206";
    private $fileName2 = "201205";
    private $fileName3 = "201207";

    function setUp() {
        @unlink($this->storage($this->fileName1));
        @unlink($this->storage($this->fileName2));
        @unlink($this->storage($this->fileName3));
    }

    function tearDown() {
        @unlink($this->storage($this->fileName1));
        @unlink($this->storage($this->fileName2));
        @unlink($this->storage($this->fileName3));
    }

    function testBucketCreatesNewFileOnFirstFlight()
    {
        $tFilename = $this->storage($this->fileName1);
        $tFlight1 = strtotime($this->testFlight1);

        $bucket = new Bucket($tFlight1);
        $this->assertFalse(file_exists($tFilename));
        $bucket->addFlights(array($tFlight1));
        $this->assertTrue(file_exists($tFilename));
        $this->assertEqual($bucket->getLastFlight(), $tFlight1);
        $this->assertEqual($bucket->flightCount(), 1);
        $this->assertEqual($bucket->getFileName(), "201206");
    }
    
    function testLoadBucketFromStorage()
    {
        $tFlight1 = strtotime($this->testFlight1);
        $bucket = new Bucket($tFlight1);
        $bucket->addFlights(array($tFlight1, $tFlight1 + 120));

        $bucket2 = new Bucket($tFlight1);
        $this->assertEqual($bucket2->getLastFlight(), $tFlight1 + 120);
        $this->assertEqual($bucket2->flightCount(), 2);
        $this->assertEqual($bucket->getFileName(), "201206");
    }

    function testBucketReturnsFollowingFlights()
    {
        $tFlight0 = strtotime($this->testFlight0);
        $tFlight1 = strtotime($this->testFlight1);
        $tFlight2 = strtotime($this->testFlight2);

        $bucket1 = new Bucket($tFlight0);
        $tExtraFlights1 = $bucket1->addFlights(array($tFlight0, $tFlight1, $tFlight1 + 120, $tFlight2));
        $this->assertEqual($tExtraFlights1, array($tFlight1, $tFlight1 + 120, $tFlight2));
        $this->assertEqual($bucket1->flightCount(), 1);
        $this->assertEqual($bucket1->getFileName(), "201205");

        $bucket2 = new Bucket($tExtraFlights1[0]);
        $tExtraFlights2 = $bucket2->addFlights($tExtraFlights1);
        $this->assertEqual($tExtraFlights2, array($tFlight2));
        $this->assertEqual($bucket2->flightCount(), 2);
        $this->assertEqual($bucket2->getFileName(), "201206");
    }

    function testBucketReturnsPreviousFlights()
    {
        $tFlight0 = strtotime($this->testFlight1);
        $tFlight1 = $tFlight0 + 120;
        $tFlight2 = $tFlight1 + 120;

        $bucket = new Bucket($tFlight0);
        $bucket->addFlights(array($tFlight0, $tFlight1, $tFlight2));
        $this->assertEqual($bucket->getPreviousFlight($tFlight2), $tFlight1);
        $this->assertEqual($bucket->getPreviousFlight($tFlight1), $tFlight0);
        $this->assertEqual($bucket->getPreviousFlight($tFlight0), null);
    }

    function testBucketReturnsPreviousBucket()
    {
        $tFlight2 = strtotime($this->testFlight2);
        $tFlight1 = $tFlight2 - 120;
        $tFlight0 = $tFlight1 - 120;

        $bucket1 = new Bucket($tFlight1);
        $bucket1->addFlights(array($tFlight0, $tFlight1));
        $bucket2 = new Bucket($tFlight2);
        $bucket2->addFlights(array($tFlight2));
        $this->assertEqual($bucket2->getPrevious(), $bucket1);
        $this->assertEqual($bucket1->getPrevious(), null);
    }

    private function storage($fileName)
    {
        return GlobalStorage::$storageRoot.$fileName.".php";
    }
}
?>