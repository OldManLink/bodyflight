<?php
class Log
{
	private $filePath;
	
    function Log($file_path) 
    {
    	$this->filePath = $file_path;
    }

    function message($text)
    {
    	$fp = fopen($this->filePath, 'a');
		fwrite($fp, $text);    	
		fclose($fp);
    }
}
?>