<?php

namespace ProgrammesPlant;

require_once dirname(dirname(dirname(__FILE__))) . '/config/params.php';

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
	  * @param string $module_code
	  * @return object $module simplexml object
	  */ 
	 public function get_module_data($module_code)
	 {
	 	$url = '';
	 	if ($this->api_target == '')
	 	{
		 	$url = \Params::$module_data_url . $module_code . '.xml';
	 	}
	 	else
	 	{
		 	$url = $this->api_target . $module_code . '.xml';
	 	}
	 	$response = $this->request($url);
	 	$module = simplexml_load_string($response);
	 	return $module;
	 }
	 
	 /**
	  * Get a module's data from the module cataglogue by its code, and return it as an object
	  * 
	  * @param string $module_code
	  * @return string $synopsis
	  */ 
	 public function get_module_synopsis($module_code)
	 {
	 	$url = '';
	 	if ($this->api_target == '')
	 	{
		 	$url = \Params::$module_data_url . $module_code . '.xml';
	 	}
	 	else
	 	{
		 	$url = $this->api_target . $module_code . '.xml';
	 	}
	 	$response = $this->request($url);
	 	$module = simplexml_load_string($response);
	 	return (string) $module->synopsis;
	 }
	 
	 /**
	  * Get a module's data from the module cataglogue by its code, and return it as an object
	  * 
	  * @param string $pos_code
	  * @param string $session
	  * @param string $campus_id
	  * @param string $institution_id
	  * @param string $pos_version
	  * @return object json object
	  */
	 public function get_programme_modules($pos_code, $session, $campus_id='1', $institution_id='0122', $pos_version='1')
	 {
	 	$url = '';
	 	// no api_target specified so use the one specified in the config
	 	if ($this->api_target == '')
	 	{
		 	$url = \Params::$programme_module_base_url . \Params::$pos_code_param . '=' . $pos_code . '&' . \Params::$version_param . '=' . $session . '&' . \Params::$instituation_param . '=' . $institution_id . '&' . \Params::$campus_param . '=' . $campus_id . '&' . \Params::$session_param . '=' . $pos_version . '&format=json';
	 	}
	 	else
	 	{
		 	$url = $this->api_target;
	 	}
	 	$response = $this->request($url);
	 	return json_decode($response);
	 }
}

class ModuleDataException extends \Exception {}
