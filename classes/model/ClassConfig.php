<?php
if (!defined('APP_SCOPE')) die('Direct access not allowed!');
/**
 * @author markus
 * read the configfile and define named constants
 */
final class config
{
	/**
	 * @return boolean
	 */
	public function __construct()
	{
		$config = file('local.config.ini', FILE_SKIP_EMPTY_LINES);
		$bol = FALSE;
		foreach ($config as $linenr => $value)
		{
			if (!preg_match('/^#/', $value))
			{
				$tempval = preg_split('/,/', trim($value));
				if (!defined($tempval['0']))
				{
					define($tempval['0'], $tempval['1']);
					$bol = TRUE;
				}
			}
		}
		return $bol;
	}
}
/* filelocation: classes/model/ClassConfig.php */
/* end of file */