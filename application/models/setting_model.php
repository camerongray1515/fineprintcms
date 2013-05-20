<?php
class Setting_model extends CI_Model {
	function get($name)
	{
		$this->db->select('value');
		$this->db->where('name', $name);
		$database_response = $this->db->get('settings');
		
		if ($database_response->num_rows() != 1)
		{
			return FALSE;
		}
		
		$setting = $database_response->row();
		
		return $setting->value;
	}
}
