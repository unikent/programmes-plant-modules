<?php

$module_data_obj = new ModuleData();

$session = '2014';

$type = 'ug';

$programmes = array(1 => 'XYZ123');

foreach ($programmes as $programme_id => $programme_code)
{
	// set things up in test mode
	$url = 'path/to/programme-module/data/data.json';
	
	$programme_modules = $module_data_obj->get_programme_modules($url, $programme_code, $session);
	
	// loop through each of the modules and get its synopsis, adding it to the object for output
	$url = 'uri/path/to/module/data/';
	foreach ($programme_modules->response->rubric->compulsory_modules->module as $index => $module)
	{
		// the synopsis
		$module->synopsis = $module_data_obj->get_module_synopsis($url, $module->{'-code'});
		// add synopsis to the object
		$programme_modules->response->rubric->compulsory_modules->module[$index]->synopsis = $module->synopsis;
	}
	
	// store complete dataset for this programme in cache
	$cache_key = "programme-modules.$type-$session-$programme_id";
	Cache::put($cache_key, $programme_modules, 2628000);

}


