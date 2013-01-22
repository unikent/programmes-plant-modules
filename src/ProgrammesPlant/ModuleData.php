<?php

namespace ProgrammesPlant;

/**
 * ProgrammesPlant
 * 
 * Provides a simple API for the Programmes Plant REST API.
 */
class ModuleData
{

	/**
	 * Persists the cURL object.
	 */
	public $curl = false;

	/**
	 * The location the API is at.
	 */
	public $api_target = '';

	/**
	 * Boolean that sets if we want to use a proxy in CURL
	 */
	public $proxy = false;

	/**
	 * The location of a HTTP proxy if required.
	 */
	public $proxy_server = '';

	/**
	 * The port of a HTTP proxy if required.
	 */
	public $proxy_port = '';
	
	/**
	* test mode
	*/
	public $test_mode = false;

	/**
	* Set a HTTP proxy for the request.
	* 
	* @param string $proxy_server The URL of the proxy server.
	* @param int $port The port of the proxy server.
	*/
	public function set_proxy($proxy_server, $proxy_port = 3128) 
	{
		$this->proxy = true;
		$this->proxy_server = $proxy_server;
		$this->proxy_port = $proxy_port;
	}
	
	public function request($uri)
	{
		// test mode uses a local module xml file
		if ($this->test_mode)
		{
			return file_get_contents($uri);
		}
		else
		{
			return curl_request($uri);
		}
		return false;
	}
	
	/**
	* Runs a cURL request.
	* 
	* The library here automatically sets CURLOPT_RETURNTRANSFER and CURLOPT_FOLLOWLOCATION.
	*
	* @param string $url The URL to make the request to.
	* @return string $response The response object.
	*/
	public function curl_request($url)
	{
		$this->curl = new \Curl($url);
		
		$this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		
		$this->curl->http_method = 'get';

		if ($this->proxy)
		{
			$this->curl->proxy($this->proxy_server, $this->proxy_port);
		}
		
		return $this->curl->execute();
	}

	 /**
	  * Get a module's data from the module cataglogue by its code, and return it as an object
	  * 
	  * @param string $url
	  * @return object $module simplexml object
	  */ 
	 public function get_module_data($url)
	 {
	 	$module = simplexml_load_string($response);
	 	return $module;
	 }
	 
	 /**
	  * Get a module's data from the module cataglogue by its code, and return it as an object
	  * 
	  * @param string $url
	  * @return string $synopsis
	  */ 
	 public function get_module_synopsis($url)
	 {
	 	$response = $this->request($url);
	 	$module = simplexml_load_string($response);
	 	return (string) $module->synopsis;
	 }
	 
	 /**
	  * Get a module's data from the module cataglogue by its code, and return it as an object
	  * 
	  * @param string $url
	  * @return object json object
	  */
	 public function get_programme_modules($url)
	 {
	 	$response = $this->request($url);
	 	return json_decode($response);
	 }
}

class ModuleDataException extends \Exception {}
