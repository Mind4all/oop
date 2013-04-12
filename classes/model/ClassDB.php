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
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
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
}
// filelocation: classes/model/ClassDB.php
// end of file