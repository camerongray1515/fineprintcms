<?php
class Snippets_model extends CI_Model {
	function get_all_snippets()
	{
		$sql = "
			SELECT
				`s`.*,
				`u_created`.`first_name` AS `created_first_name`,
				`u_created`.`last_name` AS `created_last_name`,
				`u_modified`.`first_name` AS `modified_first_name`,
				`u_modified`.`last_name` AS `modified_last_name`
			FROM
				`snippets` AS `s`,
				`users` AS `u_created`,
				`users` AS `u_modified`
			WHERE
				`s`.`created_by` = `u_created`.`id` AND
				`s`.`modified_by` = `u_modified`.`id`
			ORDER BY
				`s`.`alias` ASC;
		";
		
		$database_response = $this->db->query($sql);
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $database_response->result();
	}
	
	function add_snippet($alias, $title, $content)
	{
		$this->load->model('login_model');
		
		$alias_exists = $this->get_snippet($alias);
		
		if ($alias_exists)
		{
			throw new Exception("A snippet with that alias already exists, please pick another!", 1);
			
		}
		
		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'content'		=> $content,
			'created'		=> $date_now,
			'modified'		=> $date_now,
			'created_by'	=> $logged_in_user_id,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->insert('snippets', $data);
		
		return TRUE;
	}
	
	function get_snippet($identifier, $field='alias')
	{
		$this->db->from('snippets');
		$this->db->where($field, $identifier);
		
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() > 1)
		{
			throw new Exception('Identifier passed to get_snippet() is not unique!', 1);
		}
		else if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$snippet = $database_response->row();
		
		return $snippet;
	}
	
	function delete_snippet($snippet_id)
	{
		$this->db->where('id', $snippet_id);
		$this->db->delete('snippets');
		
		if ($this->db->affected_rows() == 0)
		{
			throw new Exception("Snippet could not be deleted, please try again, if this error persists, please contact your administrator.", 1);
		}
		
		return TRUE;
	}
	
	function update_snippet($snippet_id, $original_alias, $alias, $title, $content)
	{
		$this->load->model('login_model');
		
		// Only check alias if it has been changed!
		if ($alias != $original_alias)
		{
			$alias_exists = $this->get_snippet($alias);
			if ($alias_exists)
			{
				throw new Exception("A snippet with that alias already exists, please pick another!", 1);
				
			}
		}
		
		$snippet_exists = $this->get_snippet($snippet_id, 'id');
		if (!$snippet_exists)
		{
			throw new Exception("The snippet you are trying to save does not exist, please try again, if this error persists, please contact your administrator.", 1);
		}

		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'content'		=> $content,
			'modified'		=> $date_now,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->where('id', $snippet_id);
		$this->db->update('snippets', $data);
		
		return TRUE;
	}
}
