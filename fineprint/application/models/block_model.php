<?php
class Block_model extends CI_Model {
	function get_all_blocks()
	{
		$sql = "
			SELECT
				`b`.*,
				`u_created`.`first_name` AS `created_first_name`,
				`u_created`.`last_name` AS `created_last_name`,
				`u_modified`.`first_name` AS `modified_first_name`,
				`u_modified`.`last_name` AS `modified_last_name`
			FROM
				`blocks` AS `b`,
				`users` AS `u_created`,
				`users` AS `u_modified`
			WHERE
				`b`.`created_by` = `u_created`.`id` AND
				`b`.`modified_by` = `u_modified`.`id`
			ORDER BY
				`b`.`id` ASC;
		";
		
		$database_response = $this->db->query($sql);
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $database_response->result();
	}
	
	function add_block($alias, $title, $content, $editor)
	{
		$this->load->model('login_model');
		
		$alias_exists = $this->get_block($alias);
		
		if ($alias_exists)
		{
			throw new Exception("A block with that alias already exists, please pick another!", 1);
			
		}
		
		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'content'		=> $content,
			'editor'		=> $editor,
			'created'		=> $date_now,
			'modified'		=> $date_now,
			'created_by'	=> $logged_in_user_id,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->insert('blocks', $data);
		
		return TRUE;
	}
	
	function get_block($identifier, $field='alias')
	{
		$this->db->from('blocks');
		$this->db->where($field, $identifier);
		
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() > 1)
		{
			throw new Exception('Identifier passed to get_block() is not unique!', 1);
		}
		else if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$block = $database_response->row();
		
		return $block;
	}
	
	function delete_block($block_id)
	{
		$this->db->where('id', $block_id);
		$this->db->delete('blocks');
		
		if ($this->db->affected_rows() == 0)
		{
			throw new Exception("Block could not be deleted, please try again, if this error persists, please contact your administrator.", 1);
		}
		
		return TRUE;
	}
	
	function update_block($block_id, $original_alias, $alias, $title, $content, $editor)
	{
		$this->load->model('login_model');
		
		// Only check alias if it has been changed!
		if ($alias != $original_alias)
		{
			$alias_exists = $this->get_block($alias);
			if ($alias_exists)
			{
				throw new Exception("A block with that alias already exists, please pick another!", 1);
				
			}
		}
		
		$block_exists = $this->get_block($block_id, 'id');
		if (!$block_exists)
		{
			throw new Exception("The block you are trying to save does not exist, please try again, if this error persists, please contact your administrator.", 1);
		}

		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'content'		=> $content,
			'editor'		=> $editor,
			'modified'		=> $date_now,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->where('id', $block_id);
		$this->db->update('blocks', $data);
		
		return TRUE;
	}
}
