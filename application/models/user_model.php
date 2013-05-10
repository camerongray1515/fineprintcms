<?php
class User_model extends CI_Model {
	function add_user($first_name, $last_name, $username, $email, $password, $role)
	{
		$this->load->library('phpass');
		
		if ($this->user_exists($username))
		{
			throw new Exception("A user with that username already exists, please pick another", 1);
		}
		
		$password_hash = $this->phpass->hash($password);
		
		$data = array(
			'first_name'		=> $first_name,
			'last_name'			=> $last_name,
			'username' 			=> $username,
			'email'				=> $email,
			'password_hash'		=> $password_hash,
			'last_logged_in'	=> date("Y-m-d H:i:s"),
			'last_ip'			=> $this->input->ip_address(),
			'role'				=> $role
		);
		
		$this->db->insert('users', $data);
	}
	
	function check_credentials($username, $password)
	{
		$this->load->library('phpass');
		
		$this->db->select('password_hash');
		$this->db->from('users');
		$this->db->where('username', $username);
		
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$database_response = $database_response->row();
		$password_hash = $database_response->password_hash;
		
		return $this->phpass->check($password, $password_hash);
	}
	
	function user_exists($username)
	{
		$this->db->where('username', $username);
		
		$database_response = $this->db->get('users');
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;	
		}
		
		return TRUE;
	}

	function get_user($identifier, $field='id')
	{
		$this->db->from('users');
		$this->db->where($field, $identifier);
		$database_response = $this->db->get();
		
		if ($database_response->num_rows() > 1)
		{
			throw new Exception('Identifier passed to get_user() is not unique!', 1);
		}
		else if ($database_response->num_rows() == 0)
		{
			return FALSE;
		}
		
		$user = $database_response->row();
		
		return $user;
	}
}
