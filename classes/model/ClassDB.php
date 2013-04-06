<?php
if (!defined('APP_SCOPE')) die('Direct access not allowed!');
final class db {
	var $connid;
	var $res;
	/**
	  * @return $connid
	  */
	public function dbConnect()
	{
		if(!$this->connid = mysql_connect(DB_SYSTEM, DB_USER, DB_PASS)) {
			echo "Fehler beim Verbinden...";
		}
		// set the charset to utf-8
		mysql_set_charset("UTF8", $this->connid);
		return $this->connid;
	}
	/**
	  * @param $db
	  * @return boolean
	  */
	public function selectDB($dbConnect)
	{
		if (!mysql_select_db($dbConnect, $this->connid)) {
			return false;
		} else {
			return true;
		}
	}
	/**
	  * @param $sql
	  * @return $res
	  */
	public function sql($sql)
	{
		if (!$this->res = mysql_query($sql, $this->connid)) {
			return mysql_error();
		}
		return $this->res;
	}
}
/* filelocation: classes/model/ClassDB.php */
/* end of file */