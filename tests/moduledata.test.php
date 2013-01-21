<?php

use \ProgrammesPlant\ModuleData as ModuleData;

class TestModuleData extends PHPUnit_Framework_TestCase 
{

	public static function setUpBeforeClass()
	{
	}

	public function tearDown()
	{
	}
	
	public function testGetModuleSynopsisReturnsCorrectString()
	{
		$module_data = new ModuleData();
		$module_data->api_target = dirname(__FILE__) . '/data/';
		$module_data->test_mode = true;
		$synopsis = $module_data->get_module_synopsis('AC300');
		$this->assertStringStartsWith('This is the foundation module for the Accounting programme.', $synopsis);
	}
	
	public function testGetProgrammeModulesNotNull()
	{
		$module_data = new ModuleData();
		$module_data->api_target = dirname(__FILE__) . '/data/programme_modules.json';
		$module_data->test_mode = true;
		$programme_modules = $module_data->get_programme_modules('XYZ123', '2014');
		$this->assertNotNull($programme_modules);
	}
}