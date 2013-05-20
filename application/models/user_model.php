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
	
	function update_password($current_password, $new_password, $confirm_new_password)
	{
		$this->load->library('phpass');
		$this->load->model('login_model');
		
		// Check if new password is blank
		if (trim($new_password) == '')
		{
			throw new Exception("You cannot have a blank password!", 1);
		}
		
		// Check new passwords match
		if ($new_password != $confirm_new_password)
		{
			throw new Exception('New passwords do not match, please correct this and try again!', 1);
		}
		
		$logged_in_user = $this->login_model->get_logged_in_user();
		
		// Check the supplied value for "current_password" is correct
		$current_password_hash = $logged_in_user->password_hash;
		if (!$this->phpass->check($current_password, $current_password_hash))
		{
			throw new Exception("The password you supplied for 'Current Password' is incorrect.", 1);
		}
		
		// Error checking complete so now update the password
		$new_password_hash = $this->phpass->hash($new_password);
		
		$this->db->where('id', $logged_in_user->id);
		$this->db->update('users', array('password_hash' => $new_password_hash));
		
		return TRUE;
	}

	function update_user($user_id, $first_name, $last_name, $username, $email)
	{
		if (trim($first_name) == "" || trim($last_name) == "" || trim($username) == "" || trim($email) == "")
		{
			throw new Exception("You must fill in all the fields for the profile information!", 1);
		}
		
		$data = array(
			'first_name'	=> $first_name,
			'last_name'		=> $last_name,
			'username'		=> $username,
			'email'			=> $email
		);
		
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
		
		return TRUE;
	}
}
