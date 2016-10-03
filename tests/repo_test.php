<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/Repository.php');

class TestOfRepository extends UnitTestCase
{
    private $storagePath = "../storage/";
    private $testFlight0 = "2012-05-31 23:59:59";
    private $testFlight1 = "2012-06-05 12:34:56";
    private $testFlight2 = "2012-07-01 00:00:00";
    private $fileName = "201206";

    function setUp() {
        @unlink($this->storage());
    }

    function tearDown() {
        @unlink($this->storage());
    }

    function testRepoCreatesNewFileOnFirstFlight()
    {
        $tFilename = $this->storage();
        $tFlight1 = strtotime($this->testFlight1);

        $repo = new Repository($tFlight1);
        $this->assertFalse(file_exists($tFilename));
        $repo->addFlights(array($tFlight1));
        $this->assertTrue(file_exists($tFilename));
        $this->assertEqual($repo->getLastFlight(), $tFlight1);
        $this->assertEqual($repo->flightCount(), 1);
        $this->assertEqual($repo->getFileName(), "201206");
    }
    
    function testLoadRepoFromStorage()
    {
        $tFlight1 = strtotime($this->testFlight1);
        $repo = new Repository($tFlight1);
        $repo->addFlights(array($tFlight1, $tFlight1 + 120));

        $repo2 = new Repository($tFlight1);
        $this->assertEqual($repo2->getLastFlight(), $tFlight1 + 120);
        $this->assertEqual($repo2->flightCount(), 2);
        $this->assertEqual($repo->getFileName(), "201206");
    }

    function testRepoReturnsFollowingFlights()
    {
        $tFlight0 = strtotime($this->testFlight0);
        $tFlight1 = strtotime($this->testFlight1);
        $tFlight2 = strtotime($this->testFlight2);

        $repo1 = new Repository($tFlight0);
        $tExtraFlights1 = $repo1->addFlights(array($tFlight0, $tFlight1, $tFlight1 + 120, $tFlight2));
        $this->assertEqual($tExtraFlights1, array($tFlight1, $tFlight1 + 120, $tFlight2));
        $this->assertEqual($repo1->flightCount(), 1);
        $this->assertEqual($repo1->getFileName(), "201205");

        $repo2 = new Repository($tExtraFlights1[0]);
        $tExtraFlights2 = $repo2->addFlights($tExtraFlights1);
        $this->assertEqual($tExtraFlights2, array($tFlight2));
        $this->assertEqual($repo2->flightCount(), 2);
        $this->assertEqual($repo2->getFileName(), "201206");

        @unlink($this->storagePath.$repo1->getFileName().".php");
    }

    function storage()
    {
        return $this->storagePath.$this->fileName.".php";
    }
}
?>