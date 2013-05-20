<?php
class Editor_model extends CI_Model {
	function get_editor($editor_id)
	{
		$editor_id = ($editor_id) ? $editor_id : $this->get_default_editor_id();
				
		$this->db->select('include_code, scripts');
		$this->db->where('id', $editor_id);
		$database_response = $this->db->get('editors');
		
		$code = ''; // Output nothing for basic textarea
		$scripts = array();
		if ($database_response->num_rows() == 1)
		{
			$editor = $database_response->row();
			$code = $editor->include_code;
			$scripts = json_decode($editor->scripts);
		}
		
		$editor = new stdClass();
		$editor->scripts = $scripts;
		$editor->code = $code;
		$editor->id = $editor_id;
		
		return $editor;
	}
	
	function editor_list()
	{		
		$editors = array();
		
		$this->db->select('id, name');
		$database_response = $this->db->get('editors');
		
		if ($database_response->num_rows >= 1)
		{
			$additional_editors = $database_response->result();
			$editors = array_merge($editors, $additional_editors);
		}
		
		return $editors;
	}
	
	function get_default_editor_id()
	{
		$this->load->model('setting_model');
		
		$editor_id = $this->setting_model->get('default_editor');
		
		if (!$editor_id)
		{
			$editor_id = -1; // Fall back to basic textarea
		}
		
		return $editor_id;
	}
}
