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
	
	function update($name, $value)
	{
		$this->db->where('name', $name);
		$this->db->update('settings', array('value' => $value));
		
		if ($this->db->affected_rows() != 1)
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	function batch_update($data)
	{
		$success = TRUE;
		foreach($data as $setting => $value)
		{
			if (!$this->update($setting, $value))
			{
				$success = FALSE;
			}
		}
		
		return $success;
	}
}
