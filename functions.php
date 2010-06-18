<?php

	/**
	 * Recover PC Base Functions
	 * 
	 * PHP version 5
	 *
	 * LICENSE: Contained in the root folder under the file license.txt
	 * 
	 * @author	Michael Berkompas, mberkom@gmail.com
	 * @copyright 2010 Michael Berkompas
	 */


	function input_get($name) {
		return addslashes($_GET[$name]);
	}
	
	function print_pre($a) {
		echo "<pre>";
		print_r((array)$a);
		echo "</pre>";
	}
	
	function db_connect($db) {
		mysql_connect($db['server'], $db['username'], $db['password']) or die(mysql_error());
		mysql_select_db($db['database']) or die(mysql_error());
	}
	
	function db_query_row($sql) {
		$result = mysql_query($sql) or die("Query failed with error: ".mysql_error());
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		return (is_array($row)) ? $row : false;
	}
	
	function db_query_rows($sql) {
		$result = mysql_query($sql) or die("Query failed with error: ".mysql_error());
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$r_array[] = $row;
		} 
		return (is_array($r_array)) ? $r_array : false;
	}
	
	function is_stolen($hash) {
		return (db_query_row("SELECT * FROM ".DEVICETABLE." WHERE hash = '{$hash}' AND is_stolen > 0")) ? true : false;
	}
	
	function record_location($hash) {
		$device = db_query_row("SELECT * FROM ".DEVICETABLE." WHERE hash = '{$hash}'");
		
		$current_ip = $_SERVER['REMOTE_ADDR'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$unixtime = strtotime("now");

		mysql_query("
			INSERT INTO ".LOCATIONTABLE."
			(device_id, user_agent, ip, unixtime)
			VALUES (
				{$device['id']}, 
				\"{$user_agent}\", 
				\"{$current_ip}\",
				{$unixtime}
			)
		") or die(mysql_error());
	}
?>