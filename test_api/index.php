<?php
		
		/**************
		 *
		 * Testing API end-point
		 * Considering on the same server here
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
		
		$api_secret = 'your_api_key';
		//$host = 'http://localhost/api/metrologie/salleb01';
		$host = 'https://api-labo.ladrome.fr/api/metrologie/salleb01';
		$enable_ssl = true;
		$enable_ssl_withoutcert = false;
		$cert = getcwd() . '/../cert/GlobalSignRootCA.crt'; //public key of Root CA. This includes ladrome.fr
		
		
		/**************
		 *
		 * API test call
		 *
		 **************/
		 
		$headers[] = "HTTP_API_KEY: " . getApiKey($api_secret);
		
		
		$requete = curl_init($host);
		curl_setopt($requete, CURLOPT_HEADER, 0);
		curl_setopt($requete, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($requete, CURLOPT_HTTPHEADER, $headers);
		
		
		//SSL management
		if ($enable_ssl) {
			if ($enable_ssl_withoutcert) {
				curl_setopt($requete, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($requete, CURLOPT_SSL_VERIFYHOST, 0); //0: Does not check that the common name exists
			}
			else {
				curl_setopt($requete, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($requete, CURLOPT_SSL_VERIFYHOST, 2); //2: Check that the common name exists and that it matches the host name of the server
				curl_setopt($requete, CURLOPT_CAINFO, $cert);
			}
		}
		
		
		//For testing purpose, display the API response in browser
		header("Content-Type: application/json; charset=utf-8");
		$response = curl_exec($requete);
		
		if ($response === false) {
			echo "{\"erreur\":\"Error Curl :" . curl_error($requete) . "\"}";
		}
		else {
			if (strlen($response) == 0) {
				echo "{\"error\":\"Empty string\"}";
			}
			else {
				echo $response;
			}
		}
		
		curl_close($requete);
		
?>