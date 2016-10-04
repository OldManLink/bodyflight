<?php
require_once('../../simpletest/autorun.php');
require_once('../classes/GlobalStorage.php');

GlobalStorage::$storageRoot = "../tests/storage/";

class AllTests extends TestSuite
{
    function AllTests()
    {
        $this->TestSuite('All tests');
        $this->addFile('bucket_test.php');
        $this->addFile('repo_test.php');
    }
}
?>
