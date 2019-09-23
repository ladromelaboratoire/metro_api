# API quick documentation

## User section
### Purpose

This is an HTTP REST API over SSL to get temparature and humidity accross various places of the laboratory
The API check if user is granted for access and if the place exists.

### API calls

You need your public API key which is calculated according to the method described in `api_key_calculator.php` file.
The API public key is sent using `HTTP_API_KEY` custom HTTP headers.

The calls are made using `GET` method following this schema:
`<host>/api/metrologie/<room_id>`

### Responses
All request get a proper HTTP status code :
  - `200` OK, Data sent
  -	`202` Place does not exist, places list included in the answer
  -	`401` Authentication failed
  -	`403` Authenticated but access forbidden to the requested method
  
All responses includes a JSON object such as this one. See the API files for more examples.

```json
{
	"requester": {
		"login": "your_login",
		"origin": "::1"
	},
	"request": {
		"status_code": 200,
		"message": "ok",
		"date": "2019-09-13T13:50:57+00:00"
	},
	"data": {
		"place": "salle001",
		"temperature": 21.57,
		"temperature_unit": "degC",
		"temperature_date": "2019-09-13T13:49:53+00:00",
		"humidity": 60.57,
		"humidity_unit": "%HR",
		"humidity_date": "2019-09-13T13:50:23+00:00"
	}
}
```

### Access
The access must be granted by owner providing a login and a private key.


### Testing
2 ways to test the API : the included PHP script or [SoapUI](https://www.soapui.org/downloads/soapui.html) using the endpoint explorer.

```php
<?php
		
		require '../api/api_key_calculator.php';
		
		/**************
		 *
		 * Variables to adapt to your environment
		 *
		 **************/
		
		$api_secret = 'your_private_key';
		$host = 'https://localhost/api/metrologie/salleb01';
		$enable_ssl = true; //enabling SSL support
		$enable_ssl_withoutcert = false; //enabling self signed certs
		$cert = getcwd() . '/../cert/GlobalSignRootCA.crt'; //public key of Root CA.
		
		
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
			echo "{\"error\":\"Curl error: " . curl_error($requete) . "\"}";
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
```
### File list

 ```
API
│   readme_api.md
│
└───api
│   │   api_key_calculator.php
│   
└───cert
│   │   GlobalSignRootCA.crt
│   
└───test_api
│   │   index.php
│
└───api_response_samples
    │   Auth_fail_call.json
    │   Correct_call.json
    │   Forbidden_calll.json
    │   Unknown_place.json

```

### Requirements
No requirement to consume this API except beeing able to manage HTTP REST APIs and generate custom HTTP headers.

