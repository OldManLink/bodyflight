<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/Repository.php');

class TestOfRepository extends UnitTestCase
{
    function setUp() {
        @unlink("../storage/repository.php");
    }

    function tearDown() {
        @unlink("../storage/repository.php");
    }

    function testRepoCreatesNewFileOnFirstMessage()
    {
        $log = new Repository();
        $this->assertFalse(file_exists("../storage/repository.php"));
        $log->addFlights(array());
        $this->assertTrue(file_exists("../storage/repository.php"));
		$contents = file("../storage/repository.php");
        $this->assertEqual($contents[0], "O:10:\"Repository\":3:{s:23:\" Repository bucketNames\";a:0:{}s:22:\" Repository lastFlight\";i:0;s:17:\" Repository dirty\";b:1;}");
    }
}
?>