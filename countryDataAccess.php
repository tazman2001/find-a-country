<?php

/*model */

class countryDataAccess {

	private $mysqli = null;
	private $prep = null;

		function __construct() {
			try
			{
				$this->mysqli = new mysqli("localhost", "root", "", "countries");
			} catch (Exception $e) {
				die($ex->getMessage());
			}
	  	}


	  	function insert($sql) {
	  		$result = false;
	  		try {
	  			$result = $this->mysqli->query($sql);
	  		} catch(Exception $e) {
	  			die($e->getMessage());
	  		}
	  		
	  	}


	  	function select($sql) {
	  		$result = false;
	    	try {
	    			$prep = $this->mysqli->query($sql);
	      			
	      			$result = $prep->fetch_all(MYSQLI_ASSOC);
	      			
				} catch(Exception $e) {
					die($e->getMessage());
				}
			$prep = null;
			return($result);
		}

	  	function __destruct() {
	  		if($this->mysqli !== null) {
	  			$this->mysqli = null;
	  		}
  	}

  	function callApi($url) {
		$ch = curl_init(); 

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		#curl_setopt($ch, CURLOPT_HTTPHEADER, [ "Authorization: Basic ".base64_encode($this->api.":".$this->password), ]);


		$result=curl_exec ($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		curl_close ($ch);

		return($result);
	}
}