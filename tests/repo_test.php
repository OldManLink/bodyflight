<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/Repository.php');

class TestOfRepository extends UnitTestCase
{
    private $testFlight1 = "2012-06-05 12:34:56";
    private $testFlight2 = "2012-07-05 12:34:56";

    function setUp() {
        @unlink($this->storage());
    }

    function tearDown() {
        @unlink($this->storage());
    }

    function testRepoCreatesNewFileOnFirstMessage()
    {
        $tFlight1 = strtotime($this->testFlight1);
        $tFlight2 = strtotime($this->testFlight2);
        $log = new Repository();
        $this->assertFalse(file_exists($this->storage()));
        $log->addFlights(array($tFlight1, $tFlight2));
        $this->assertTrue(file_exists($this->storage()));
		$contents = file($this->storage());
        $this->assertEqual($contents[0], "O:10:\"Repository\":3:{s:23:\" Repository bucketNames\";a:2:{i:0;s:6:\"201206\";i:1;s:6:\"201207\";}s:22:\" Repository lastFlight\";i:0;s:23:\" PersistentObject dirty\";b:1;}");
    }

    function storage()
    {
        return GlobalStorage::$storageRoot."repository.php";
    }
}
?>