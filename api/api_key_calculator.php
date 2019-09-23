<?php
	
	/****************
	 *
	 * Client API key calculator
	 *
	 * To access the API, you need the correct API public key. This is calculated using hashing methods.
	 * The API key is the HASH of the salted private key. The Hash algorithm used is Blowfish.
	 *
	 * Generating SALT : "LDA26." + DATE(Y.m.d) + ".LIMS"
	 * Cyphering your account private key using BLOWFISH algorithm
	 * 
	 * Sample data :
	 *		Date : 2019.08.31 => salt : LDA26.2019.08.31.LIMS
	 *		private key: "Thepassword"
	 *		Blowfish parameters : cost 10
	 *
	 * Here below a PHP function which returns the API key
	 * 
	 *****************/
	 
	 function getApiKey ($priv_key) {
	
		$salt = 'LDA26.' . date('Y.m.d') . '.LIMS';
		$crypt_param = '$2y$10$' . $salt . '$';
		
		//removing extra data to get key
		return substr(crypt($priv_key, $crypt_param), strlen($crypt_param));
	 }
	 
	 /**************
	  *
	  * Sample output :
	  *
	  * $priv_key = "Thepassword"; $salt = "LDA26.2019.08.31.LIMS";
	  * echo getApiKey($priv_key);
	  *
	  * returns UCDQ7K.CdpCfVNMs/bC5s58trOI1Ooe
	  *
	  *****************/
	 
?>