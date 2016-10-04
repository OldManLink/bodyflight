<?php
require_once('GlobalStorage.php');
abstract class PersistentObject
{
    private $dirty = false;

    abstract protected function storage();
    abstract protected function copyFrom($anotherObject);

    protected function getStorageRoot()
    {
        return GlobalStorage::$storageRoot;
    }

    protected function saveToStorage()
    {
        if($this->isDirty())
        {
            file_put_contents($this->storage(), serialize($this));
            $this->makeClean();
        }
    }

    protected function readFromStorage()
    {
        if(file_exists($this->storage()))
        {
            $this->copyFrom(unserialize(file_get_contents($this->storage())));
            $this->makeClean();
        }
    }

    protected function isDirty()
    {
        return $this->dirty;
    }

    protected function makeDirty()
    {
        $this->dirty = true;
    }

    protected function makeClean()
    {
        $this->dirty = false;
    }
}
?>