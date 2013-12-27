<?php
// British Gas Remote Heating Control API - Copyright Jordan Cook 2013
// You use this at your own risk, this is not endorsed by british gas!

class RHC {
	private $ApiSession;
	private $ApiURL = "https://myhomeapi.britishgas.co.uk/v5/";
	private $ApiUsername;
	
	function __construct($username, $password) {
		// create a new curl client
		$bg = curl_init();

		curl_setopt($bg, CURLOPT_URL, $this->ApiURL . 'login'); // set the post url
		curl_setopt($bg, CURLOPT_POST, 3); // set the field count
		curl_setopt($bg, CURLOPT_POSTFIELDS, "username={$username}&password={$password}&caller=iphone"); // send the data
		curl_setopt($bg, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($bg, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($bg, CURLOPT_SSL_VERIFYHOST, false);		
		$output = curl_exec($bg); // execute
		
		if ($output == false) {
			die("Error: " . curl_error($bg));
			curl_close($bg);
		} else {
			$output = json_decode($output);
			if (isset($output->error->reason)) {
				return $output->error->reason;
			} else {
				$this->ApiSession = $output->ApiSession;
				$this->ApiUsername = $output->username;
				
				return true;
			}
		}

	}
	
	function getCurrentTemperatures() {
		// create a new curl client
		$bg = curl_init();

		curl_setopt($bg, CURLOPT_URL, $this->ApiURL . 'users/' . $this->ApiUsername . '/widgets/temperature?precision=0.1');
		curl_setopt($bg, CURLOPT_COOKIE, "ApiSession={$this->ApiSession}");
		curl_setopt($bg, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($bg, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($bg, CURLOPT_SSL_VERIFYHOST, false);		
		$output = json_decode(curl_exec($bg)); // execute
		
		if (isset($output->error)) {
			return false;
		} else {
			return $output;
		}
	}
	
	function getDeviceList() {
		// create a new curl client
		$bg = curl_init();

		curl_setopt($bg, CURLOPT_URL, $this->ApiURL . 'users/' . $this->ApiUsername . '/widgets/climate?precision=0.5');
		curl_setopt($bg, CURLOPT_COOKIE, "ApiSession={$this->ApiSession}");
		curl_setopt($bg, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($bg, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($bg, CURLOPT_SSL_VERIFYHOST, false);	
		$output = json_decode(curl_exec($bg)); // execute
		
		if (isset($output->error)) {
			return false;
		} else {
			return $output;
		}
	}
	
}