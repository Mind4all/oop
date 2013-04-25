<?php
if (!defined('APP_SCOPE')) die('Direct access not allowed!');
/**
 * @author eMKa
 * our exceptionhandling
*/
final class appexception extends Exception
{
	public function privateErrorMessage()
	{
		//error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
		.': <b>'.$this->getMessage().'</b>';
		// @ TODO may be it will be a good idea to write this to a logfile
		return $errorMsg;
	}
	public function publicErrorMessage()
	{
		//error message for the public output
		$errorMsg = $this->getMessage().' is not a valid E-Mail address.';
		return $errorMsg;
	}
}