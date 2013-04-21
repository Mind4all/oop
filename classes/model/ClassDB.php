<?php
if (!defined('APP_SCOPE')) die('Direct access not allowed!');
/**
 * @author eMKa
 * do the db stuff with mysqli
*/
final class db extends mysqli
{
	/**
	 */
	public function __construct()
	{
		parent::init();

		try
		{
			parent::options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0');
		}
		catch (appexception $e)
		{
			echo 'Setting MYSQLI_INIT_COMMAND failed: ' . $e->privateErrorMessage(); 
		}
		
		try
		{
			parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
		}
		catch (appexception $e)
		{
			echo 'Setting MYSQLI_OPT_CONNECT_TIMEOUT failed: ' . $e->privateErrorMessage();
		}
		// the params are the constants we defined earlier
		try
		{
			parent::real_connect(DB_SYSTEM, DB_USER, DB_PASS, DB_NAME);
		}
		catch (appexception $e)
		{
			echo 'Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . $e->privateErrorMessage();
		}
		// set charset to utf8
		try {
			$this->set_charset("utf8");
		} catch (appexception $e) {
			echo 'Error loading character set utf8: ' . $e->privateErrorMessage();
		}
	}
}
// filelocation: classes/model/ClassDB.php
// end of file