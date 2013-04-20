<?php
final class dbwrapper
{
	private function openConnection()
	{
		$db = new db();
		if ($db->connect_errno) {
			echo 'Failed to connect to MySQL: (' . $db->connect_errno . ') ' . $db->connect_error;
		}
		return $db;

	}
	private function closeConnection($db)
	{
		$db->close();
	}
	/**
	 * @param string $from
	 * @return string
	 * returns a commaseperated string of the available fields no comma at the last entry!
	 */
	public function getTableFields($from)
	{
		$db = $this->openConnection();

		if($result = $db->query('SELECT * FROM ' . $from . ''))
		{
			$field_cnt = $result->field_count;
			$finfo = $result->fetch_fields();
			$fields = '';
			for($i = 0; $i <= ($field_cnt - 1); $i ++)
			{

				if ($i == ($field_cnt - 1))
				{
					$fields .= $finfo[$i]->name;
				}
				else
				{
					$fields .= $finfo[$i]->name . ', ';
				}
			}
			$result->close();
		}
		$this->closeConnection($db);
		return $fields;
	}
	/**
		* @param string $from
		* @return array
		*/
	public function getDbTables($from)
	{
		$db = $this->openConnection();
		$result = $db->query('SHOW TABLES FROM ' . $from . '');
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
		$db = new db();
		// @TODO secure this function
		$data = array();
		$stmt = $db->stmt_init();
		if (!($stmt = $db->prepare('SELECT ' . $what . ' FROM ' . $from . '')))
		{
			echo "Prepare failed: (" . $db->errno . ") " . $db->error;
		}

		$stmt->execute();
		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field())
		{
			$params[] = &$row[$field->name];
		}
		
		call_user_func_array(array($stmt, 'bind_result'), $params);
		
		while ($stmt->fetch()) {
			foreach($row as $key => $val)
			{
				$c[$key] = $val;
			}
			$result[] = $c;
		}
		
		return $result;
	}
}