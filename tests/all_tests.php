<?php
require_once('../../simpletest/autorun.php');

class AllTests extends TestSuite
{
    function AllTests()
    {
        $this->TestSuite('All tests');
        $this->addFile('log_test.php');
        $this->addFile('bucket_test.php');
        $this->addFile('repo_test.php');
    }
}
?>
