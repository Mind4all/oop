<?php
/**
 * @author Klaßen
 * main application class
 */
final class index
{
	/**
	 * @TODO what do we need constants paths etc
	 */
	public function init ()
	{
		defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);
		header('Content-Type: text/html; charset=utf-8');
		//
		define('APP_SCOPE', 1);
		// include our configfile
		require_once 'config.php';
		// other Stuff
		if (!defined('ROOT'))
		{
			define('ROOT', $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']);
		}
		$this->_initSession();
		$this->_showErrors();
	}

	/**
	 * @TODO run the application
	 */
	public function run ()
	{
		// load the required classes
		spl_autoload_register('index::_autoloadmodel');
		spl_autoload_register('index::_autoloadcontrol');
		spl_autoload_register('index::_autoloadview');
		$this->_autoloadmodel('ClassDB');
	}

	/**
	 * errors are only shown if you connect local
	 */
	protected function _showErrors()
	{
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		{
			ini_set('error_reporting', E_ALL);
			error_reporting(E_ALL & ~E_NOTICE);

			ini_set('error_reporting', version_compare(PHP_VERSION, 5, '>=')
			&& version_compare(PHP_VERSION, 6, '<') ? E_ALL ^ E_STRICT : E_ALL);
			ini_set('display_errors', 'On'); // show errors
			ini_set("log_errors", 1);     // switch logging on/off
			ini_set("error_log", ROOT . "/errorlog.txt");     // logfile
		}
		else
		{
			ini_set('display_errors', 'Off'); // show errors
			ini_set("log_errors", 1);     // switch logging on/off
			ini_set("error_log", ROOT . "/errorlog.txt");     // logfile
		}

	}

	/**
	 * @TODO what else is needed for the session
	 */
	protected function _initSession()
	{
		// we want to have a session
		session_start();
		/* lightweight security check preventing session overtaking */
		if(!isset($_SESSION['prename'], $_SESSION['name'], $_SESSION['securitytoken']))
		{
			$_SESSION['prename'] = '';
			$_SESSION['name'] = 'Gast';
			$_SESSION['securitytoken'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
		}
		else
		{
			// if our token does not match we create a new sesion
			if($_SESSION['securitytoken']!== md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']))
			{
				echo 'new session created cause something wrong with session!<br>';
				session_destroy();
				$this->_initSession();
			}
		}
	}

	/**
	 * @param String
	 */
	protected static function _autoloadmodel($class)
	{
		if (is_readable(ROOT . 'classes'.DS.'model'.DS.''.$class.'.php'))
		{
			require ROOT . 'classes'.DS.'model'.DS.''.$class.'.php';
		}
	}

	/**
	 * @param String
	 */
	protected static function _autoloadcontrol($class)
	{
		if (is_readable(ROOT . 'classes'.DS.'control'.DS.''.$class.'.php'))
		{
			require ROOT . 'classes'.DS.'control'.DS.''.$class.'.php';
		}
	}

	/**
	 * @param String
	 */
	protected static function _autoloadview($class)
	{
		if (is_readable(ROOT . 'classes'.DS.'view'.DS.''.$class.'.php'))
		{
			require ROOT . 'classes'.DS.'view'.DS.''.$class.'.php';
		}
	}
}
// init an run our application
$index = new index();
$index->init();
$index->run();

//Testing below here

$db = new db();
$db->dbConnect();
$db->selectDB(DB_NAME);
/* prepare SQL statement */
$sqlStm = "SELECT
		*
		FROM
		" . USER_TABLE . "";
$result = $db->sql($sqlStm);
while($row = mysql_fetch_assoc($result)){
	foreach($row as $k => $v)
	{
		echo $k . ' => ' . $v .'<br>';
	}
}