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
        $this->db->where('active', '1');
		
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
        $this->db->where('active', '1');
		
		$database_response = $this->db->get('users');
		
		if ($database_response->num_rows() == 0)
		{
			return FALSE;	
		}
		
		return TRUE;
	}

	function get_user($identifier, $field='id')
	{
		$escaped_field = $this->db->escape_str($field);
		
		$sql = "SELECT
						`u`.*,
						`r`.`name` AS `role_name`
				FROM
						`users` AS `u`,
						`roles` AS `r`
				WHERE
						`u`.`role` = `r`.`id` AND
						`u`.`active` = 1 AND
						`u`.`$escaped_field` = ?;";
		
		$database_response = $this->db->query($sql, array($identifier));
		
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
	
	function update_profile_password($user_id, $current_password, $new_password, $confirm_new_password)
	{
		$this->load->library('phpass');
		$this->load->model('login_model');
		
		$logged_in_user = $this->login_model->get_logged_in_user();
		
		// Check the supplied value for "current_password" is correct
		$current_password_hash = $logged_in_user->password_hash;
		if (!$this->phpass->check($current_password, $current_password_hash))
		{
			throw new Exception("The password you supplied for 'Current Password' is incorrect.", 1);
		}
		
		$this->update_password($user_id, $new_password, $confirm_new_password);
		
		return TRUE;
	}

    function update_password($user_id, $new_password, $confirm_new_password)
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

        // Error checking complete so now update the password
        $new_password_hash = $this->phpass->hash($new_password);

        $this->db->where('id', $user_id);
        $this->db->where('active', '1');
        $this->db->update('users', array('password_hash' => $new_password_hash));

        // If the user updated is the logged in user, refresh the cached data for that user
        $this->load->model('login_model');
        $logged_in_user_id = $this->login_model->get_logged_in_user('id');

        if ($logged_in_user_id == $user_id)
        {
            $this->login_model->reload_logged_in_user();
        }

        return TRUE;
    }

	function update_user($user_id, $original_username, $first_name, $last_name, $username, $email)
	{
		if (trim($first_name) == "" || trim($last_name) == "" || trim($username) == "" || trim($email) == "")
		{
			throw new Exception("You must fill in all the fields for the profile information!", 1);
		}

        if ($username != $original_username && $this->user_exists($username))
        {
            throw new Exception("A user with that username already exists, please pick another", 1);
        }
		
		$data = array(
			'first_name'	=> $first_name,
			'last_name'		=> $last_name,
			'username'		=> $username,
			'email'			=> $email
		);

		$this->db->where('id', $user_id);
        $this->db->where('active', 1);
		$this->db->update('users', $data);

        // If the user updated is the logged in user, refresh the cached data for that user
        $this->load->model('login_model');
        $logged_in_user_id = $this->login_model->get_logged_in_user('id');

        if ($logged_in_user_id == $user_id)
        {
            $this->login_model->reload_logged_in_user();
        }
		
		return TRUE;
	}
	
	function update_last_session_data()
	{
		$this->load->model('login_model');
		
		$data = array(
			'last_logged_in'	=> date("Y-m-d H:i:s"),
			'last_ip'			=> $this->input->ip_address()
		);
		
		$this->db->where('id', $this->login_model->get_logged_in_user('id'));
		$this->db->update('users', $data);
	}
	
	function get_all_users()
	{
		$sql = "SELECT
						`u`.*,
						`r`.`name` AS `role_name`
				FROM
						`users` AS `u`,
						`roles` AS `r`
				WHERE
				        `u`.`active` = 1 AND
						`u`.`role` = `r`.`id`;";
		
		$database_response = $this->db->query($sql);
		
		$users = $database_response->result();
		return $users;
	}
	
	function get_allowed_controllers($user_id)
	{
		$sql = "SELECT
						`c`.`internal_name`,
						`c`.`display_name`
				FROM	
						`users` AS `u`,
						`roles` AS `r`,
						`allowed_to_access` AS `a`,
						`controllers` AS `c`
				WHERE
						`u`.`role` = `r`.`id` AND
						`a`.`role` = `r`.`id` AND
						`a`.`controller` = `c`.`id` AND
						`u`.`active` = 1 AND
						`u`.`id` = ?;";
						
		$database_response = $this->db->query($sql, array($user_id));
		
		$allowed_controllers = $database_response->result();
		
		return $allowed_controllers;
	}

    function disable_user($user_id)
    {
        // Check if this is the last role that allows access to some required controllers
        $sql = "SELECT
                    `c`.*
                FROM
                    `roles` AS `r`,
                    `allowed_to_access` AS `ata`,
                    `controllers` AS `c`,
                    `users` AS `u`
                WHERE
                    `ata`.`role` = `r`.`id` AND
                    `ata`.`controller` = `c`.`id` AND
                    `c`.`must_have_role` = 1 AND
                    `r`.`id` = `u`.`role` AND
                    `u`.`id` = ? AND
                    `u`.`active` = 1 AND
                    `c`.`id` NOT IN (
                        SELECT
                            `c`.`id`
                        FROM
                            `roles` AS `r`,
                            `allowed_to_access` AS `ata`,
                            `controllers` AS `c`,
                            `users` AS `u`
                        WHERE
                            `ata`.`role` = `r`.`id` AND
                            `ata`.`controller` = `c`.`id` AND
                            `c`.`must_have_role` = 1 AND
                            `r`.`id` = `u`.`role` AND
                            `u`.`active` = 1 AND
                            `u`.`id` != ?
                    )";

        $database_response = $this->db->query($sql, array($user_id, $user_id));
        $required_controllers = $database_response->result();

        if ($required_controllers)
        {
            $error_string = 'At least one user must have the following permissions:<ul>';
            foreach ($required_controllers as $controller)
            {
                $error_string .= "<li>{$controller->description}</li>";
            }
            $error_string .= '</ul>The user you are trying to delete is the only user that has these permissions. To resolve this, assign another user to a role that gives these permissions.  Then try deleting again';

            throw new Exception($error_string, 1);
        }

        $this->db->where('id', $user_id);
        $this->db->where('active', '1');

        $this->db->set('active', '0');
        $this->db->set('password_hash', ''); // Remove the password hash for additional security, no point leaving unneeded password hashes laying around
        $this->db->update('users');

        if (!$this->db->affected_rows())
        {
            throw new Exception();
        }
    }
}
