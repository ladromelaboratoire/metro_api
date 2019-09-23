<?php
	/**************
	 *
	 * Calculate the daily public API key
	 *
	 * Author : LDA26
	 * Version : 1.1
	 *
	 * Changelog : Add support for SSL
	 *
	 **************/
	
	require '../api/api_key_calculator.php';
	
	/**************
	 *
	 * Variables to adapt to your environment
	 *
	 **************/
	
	$api_secret = 'your_private_key';
	
	echo "HTTP_API_KEY: " . getApiKey($api_secret);

?>