<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/Log.php');

class TestOfLogging extends UnitTestCase
{
    function setUp() {
        @unlink('./test.log');
    }

    function tearDown() {
        @unlink('./test.log');
    }

    function testLogCreatesNewFileOnFirstMessage()
    {
        $log = new Log('./test.log');
        $this->assertFalse(file_exists('./test.log'));
        $log->message("Should write this to a file\n");
        $this->assertTrue(file_exists('./test.log'));
        $log->message("This, too!\n");
		$messages = file('./test.log');
        $this->assertEqual($messages[0], "Should write this to a file\n"); 
        $this->assertEqual($messages[1], "This, too!\n");         
    }
}
?>