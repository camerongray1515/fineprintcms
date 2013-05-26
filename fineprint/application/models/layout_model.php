<?php
class Layout_model extends CI_Model {
	function get_all_layouts($default_first = TRUE)
	{
		$default_order_sql = "";
		if ($default_first)
		{
			$default_order_sql = "`l`.`default` DESC,";
		}
		
		$sql = "
			SELECT
				`l`.*,
				`u_created`.`first_name` AS `created_first_name`,
				`u_created`.`last_name` AS `created_last_name`,
				`u_modified`.`first_name` AS `modified_first_name`,
				`u_modified`.`last_name` AS `modified_last_name`
			FROM
				`layouts` AS `l`,
				`users` AS `u_created`,
				`users` AS `u_modified`
			WHERE
				`l`.`created_by` = `u_created`.`id` AND
				`l`.`modified_by` = `u_modified`.`id`
			ORDER BY
				$default_order_sql
				`l`.`id` ASC;
		";
		
		$database_response = $this->db->query($sql);
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $database_response->result();
	}
	
	function set_default($default_id)
	{
		// Check the layout we are setting as default exists so we don't accidentally remove all defaults without setting a new one
		$layout_exists = $this->get_layout($default_id, 'id');
		if (!$layout_exists)
		{
			throw new Exception("The layout you are trying to set as default does not exist! Please try again.", 1);
		}
		
		// Remove all defaults
		$this->db->where('default', 1);
		$this->db->update('layouts', array('default' => 0));
		
		// Set the correct default
		$this->db->where('id', $default_id);
		$this->db->update('layouts', array('default' => 1));
		
		return TRUE;
	}
	
	function add_layout($title, $content, $editor)
	{
		$this->load->model('login_model');
		
		$title_exists = $this->get_layout($title, 'title');
		
		if ($title_exists)
		{
			throw new Exception("A layout with that title already exists, please pick another!", 1);
			
		}
		
		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'title'			=> $title,
			'content'		=> $content,
			'editor'		=> $editor,
			'created'		=> $date_now,
			'modified'		=> $date_now,
			'created_by'	=> $logged_in_user_id,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->insert('layouts', $data);
		
		return TRUE;
	}
	
	function get_layout($identifier, $field='id')
	{
		$this->db->from('layouts');
		$this->db->where($field, $identifier);
		
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() > 1)
		{
			throw new Exception('Identifier passed to get_layout() is not unique!', 1);
		}
		else if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$layout = $database_response->row();
		
		return $layout;
	}
	
	function delete_layout($layout_id)
	{
		$this->db->where('id', $layout_id);
		$this->db->delete('layouts');
		
		if ($this->db->affected_rows() == 0)
		{
			throw new Exception("Layout could not be deleted, please try again, if this error persists, please contact your administrator.", 1);
		}
		
		return TRUE;
	}
	
	function update_layout($layout_id, $original_title, $title, $content, $editor)
	{
		$this->load->model('login_model');
		
		// Only check title if it has been changed!
		if ($title != $original_title)
		{
			$title_exists = $this->get_layout($title, 'title');
			if ($title_exists)
			{
				throw new Exception("A layout with that title already exists, please pick another!", 1);
				
			}
		}
		
		$layout_exists = $this->get_layout($layout_id, 'id');
		if (!$layout_exists)
		{
			throw new Exception("The layout you are trying to save does not exist, please try again, if this error persists, please contact your administrator.", 1);
		}

		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'title'			=> $title,
			'content'		=> $content,
			'editor'		=> $editor,
			'modified'		=> $date_now,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->where('id', $layout_id);
		$this->db->update('layouts', $data);
		
		return TRUE;
	}
}
