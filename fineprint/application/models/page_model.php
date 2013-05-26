<?php
class Page_model extends CI_Model {
	function get_all_pages()
	{
		$sql = "
			SELECT
				`p`.*,
				`l`.`title` AS `layout_name`,
				`u_created`.`first_name` AS `created_first_name`,
				`u_created`.`last_name` AS `created_last_name`,
				`u_modified`.`first_name` AS `modified_first_name`,
				`u_modified`.`last_name` AS `modified_last_name`
			FROM
				`pages` AS `p`,
				`layouts` AS `l`,
				`users` AS `u_created`,
				`users` AS `u_modified`
			WHERE
				`p`.`created_by` = `u_created`.`id` AND
				`p`.`modified_by` = `u_modified`.`id` AND
				`l`.`id` = `p`.`layout`
			ORDER BY
				`p`.`id` ASC;
		";
		
		$database_response = $this->db->query($sql);
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		return $database_response->result();
	}
	
	function add_page($alias, $title, $layout_id, $content, $editor)
	{
		$this->load->model('login_model');
		
		$alias_exists = $this->get_page($alias);
		
		if ($alias_exists)
		{
			throw new Exception("A page with that alias already exists, please pick another!", 1);
			
		}
		
		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'layout'		=> $layout_id,
			'content'		=> $content,
			'editor'		=> $editor,
			'created'		=> $date_now,
			'modified'		=> $date_now,
			'created_by'	=> $logged_in_user_id,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->insert('pages', $data);
		
		return TRUE;
	}
	
	function get_page($identifier, $field='alias')
	{
		$this->db->from('pages');
		$this->db->where($field, $identifier);
		
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() > 1)
		{
			throw new Exception('Identifier passed to get_page() is not unique!', 1);
		}
		else if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$page = $database_response->row();
		
		return $page;
	}
	
	function delete_page($page_id)
	{
		$this->db->where('id', $page_id);
		$this->db->delete('pages');
		
		if ($this->db->affected_rows() == 0)
		{
			throw new Exception("Page could not be deleted, please try again, if this error persists, please contact your administrator.", 1);
		}
		
		return TRUE;
	}
	
	function update_page($page_id, $original_alias, $alias, $title, $layout_id, $content, $editor)
	{
		$this->load->model('login_model');
		
		// Only check alias if it has been changed!
		if ($alias != $original_alias)
		{
			$alias_exists = $this->get_page($alias);
			if ($alias_exists)
			{
				throw new Exception("A page with that alias already exists, please pick another!", 1);
				
			}
		}
		
		$page_exists = $this->get_page($page_id, 'id');
		if (!$page_exists)
		{
			throw new Exception("The page you are trying to save does not exist, please try again, if this error persists, please contact your administrator.", 1);
		}

		$logged_in_user_id = $this->login_model->get_logged_in_user('id');
		$date_now = date("Y-m-d H:i:s");
		
		$data = array(
			'alias'			=> $alias,
			'title'			=> $title,
			'layout'		=> $layout_id,
			'content'		=> $content,
			'editor'		=> $editor,
			'modified'		=> $date_now,
			'modified_by'	=> $logged_in_user_id
		);
		
		$this->db->where('id', $page_id);
		$this->db->update('pages', $data);
		
		return TRUE;
	}
}
