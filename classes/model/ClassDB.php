<?php
if (!defined('APP_SCOPE')) die('Direct access not allowed!');
/**
 * @author markus
 * do the db stuff with mysqli
*/
final class db extends mysqli
{
	/**
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $db
	 */
	public function __construct()
	{
		parent::init();

		if (!parent::options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0'))
		{
			die('Setting MYSQLI_INIT_COMMAND failed');
		}

		if (!parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5))
		{
			die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
		}
		// the params are the constants we defined earlier
		if (!parent::real_connect(DB_SYSTEM, DB_USER, DB_PASS, DB_NAME))
		{
			die('Connect Error (' . mysqli_connect_errno() . ') '
					. mysqli_connect_error());
		}
		$this->_setUtf8();
	}

	/**
	 * set the characterset to utf-8
	 */
	private function _setUtf8()
	{
		// change character set to utf8
		if (!$this->set_charset("utf8"))
		{
			printf("Error loading character set utf8: %s\n", $this->error);
		}
		else
		{
			printf("Current character set: %s<br>", $this->character_set_name());
		}
	}
	/**
	 * @param string $from
	 * @return string
	 * returns a commaseperated string of the available fields
	 */
	public function getTableFields($from)
	{

		if($result = $this->query('SELECT * FROM ' . $from . ''))
		{
			$finfo = $result->fetch_fields();
			$fields = '';
			foreach ($finfo as $val) {
				$fields .= $val->name .', ';			}
			$result->close();
		}
		$fields = mb_substr($fields ,0,-2);
		return $fields;
	}
	/**
	 * @param string $from
	 * @return array
	 */
	public function getDbTables($from)
	{
		$result = $this->query('SHOW TABLES FROM ' . $from . '');
		$tables = array();
		while ($daten = $result->fetch_object())
		{
			foreach ($daten as $key => $val)
			{
				array_push($tables, $val);
			}
			
		}
		$result->close();
		return $tables;
	}
	/**
	 * @param string $what
	 * @param string $from
	 * @return array
	 */
	public function sqlQuery($what, $from)
	{
		// @TODO secure this function
		$data = array();
		echo 'SELECT ' . $what . ' FROM ' . $from . '' .'<br>';
		if($result = $this->query('SELECT ' . $what . ' FROM ' . $from . ''))
		{
			$tmp = array();
			$int = 0;
			while ($daten = $result->fetch_object())
			{

				foreach ($daten as $key => $val)
				{
					$tmp[$key] = $val;
				}
				$data[$int]= $tmp;
				$int++;
			}

		}
		$result->close();
		return $data;
	}
	// @TODO evaluate!!
}
// filelocation: classes/model/ClassDB.php
// end of file