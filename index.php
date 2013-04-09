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
		// Directory seperator
		if (!defined('DS'))
		{
			define('DS',DIRECTORY_SEPARATOR);
		}
		// application root
		if (!defined('ROOT'))
		{
			define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS . basename(__DIR__) . DS);
		}
		// our app scope
		if (!defined ('APP_SCOPE'))
		{
			define('APP_SCOPE', 1);
		}
		// set to utf-8
		header('Content-Type: text/html; charset=utf-8');
		// init the session
		$this->_initSession();
		// show errors
		$this->_showErrors();
		// load the required classes
		spl_autoload_register('index::_autoloadclass');
		$this->_autoloadclass('ClassConfig');
		$this->_autoloadclass('ClassDB');
	}

	/**
	 * @TODO run the application
	 */
	public function run ()
	{
		// we need a configuration for running
		$conf = new config();
		// @TODO what else do we need for our application

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
			ini_set("error_log", ROOT . "errorlog.txt");     // logfile
		}
		else
		{
			ini_set('display_errors', 'Off'); // show errors
			ini_set("log_errors", 1);     // switch logging on/off
			ini_set("error_log", ROOT . "errorlog.txt");     // logfile
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
	protected static function _autoloadclass($class)
	{
		if(is_readable(ROOT . 'classes' . DS . 'model' . DS . $class . '.php'))
		{
			require ROOT . 'classes' . DS . 'model' . DS . $class . '.php';
		}
		elseif(is_readable(ROOT . 'classes' . DS . 'control' . DS . $class . '.php'))
		{
			require ROOT . 'classes' . DS . 'control' . DS . $class . '.php';
		}
		elseif(is_readable(ROOT . 'classes' . DS . 'view' . DS . $class . '.php'))
		{
			require ROOT . 'classes' . DS . 'view' . DS . $class . '.php';
		}
		else 
		{
			die('No suitable class found in the path!<br>');
		}
	}
}
// init an run our application
$index = new index();
$index->init();
$index->run();

//Testing below here

try
{
	$db = new db();
}
catch (Exception $e)
{
	echo 'Error: ' . $e;
}

echo '<hr>';
$tables = $db->getDbTables(DB_NAME);
var_dump($tables);
echo '<hr>fields:<br>';
$fields = $db->getTableFields(USER_TABLE);
var_dump($fields);
echo '<hr>';
$myarray = $db->sqlQuery($fields, USER_TABLE);
var_dump($myarray);
echo '<hr>';
/*
if($resultat = $db->query('SELECT * FROM user ORDER by id'))
{
	echo 'Total results: ' . $resultat->num_rows .'<br>';
	
	while ($daten = $resultat->fetch_object())
	{
		foreach ($daten as $key => $val)
		{
			echo $key . ' | ' . $val . '<br>';
		}
	}
	
	echo 'Guck<br>';
}
*/
$db->close();

