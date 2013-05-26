<?php
class Module {
	private $module_data_store = array();
	
	function set_module_data($module, $key, $value)
	{
		$this->module_data_store[$module][$key] = $value;
	}
	
	function get_module_data($module, $key)
	{
		if (isset($this->module_data_store[$module][$key]))
		{
			return $this->module_data_store[$module][$key];
		}
		
		return FALSE;
	}
}
