<?php

	/**
	 * Recover PC Action Handler
	 * 
	 * PHP version 5
	 *
	 * LICENSE: Contained in the root folder under the file license.txt
	 * 
	 * @author	Michael Berkompas, mberkom@gmail.com
	 * @copyright 2010 Michael Berkompas
	 */
	
	/* Load User Definitions */
	require("config.php");
	
	/* Load Base Functions */
	require("functions.php");
		
	/* System Definitions */
	
	$hash = input_get("hash"); // Computer id hash
	
	/* Application */
	
	// setup database
	db_connect($db);
	
	if(is_stolen($hash)) {
		record_location($hash);
		echo "Stolen";
	}
?>