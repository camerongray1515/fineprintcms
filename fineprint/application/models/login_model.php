<?php
class Login_model extends CI_Model {
	function do_login($username, $password)
	{
		$this->load->model('user_model');
		
		$correct_credentials = $this->user_model->check_credentials($username, $password);
		
		if (!$correct_credentials)
		{
			return FALSE;
		}
		
		// Store login state in session
		$user = $this->user_model->get_user($username, 'username');
		
		$this->session->set_userdata('logged_in_user_id', $user->id);
		$this->session->set_userdata('logged_in_user', $user);
		
		$this->user_model->update_last_session_data();
		
		return TRUE;
	}
	
	function get_logged_in_user($property = FALSE)
	{
		$this->load->model('user_model');
		
		$user = $this->session->userdata('logged_in_user');
				
		if (!$property)
		{
			return $user;
		}
		
		return $user->$property;
	}
	
	function is_logged_in()
	{
		$logged_in_user_id = $this->session->userdata('logged_in_user_id');
		
		if ($logged_in_user_id)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	function logout()
	{
		$this->session->unset_userdata('logged_in_user_id');
	}
	
	function is_allowed_to_access_controller($controller)
	{
		$this->load->model('user_model');
		
		$logged_in_user = $this->get_logged_in_user();
		$allowed_controllers = $this->user_model->get_allowed_controllers($logged_in_user->id);
		
		// Go through the allowed controllers and get their internal names into an array
		$allowed_controller_names = array();
		foreach($allowed_controllers as $allowed_controller)
		{
			array_push($allowed_controller_names, $allowed_controller->internal_name);
		}
		
		if (array_search($controller, $allowed_controller_names) !== FALSE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;	
		}
	}

    function reload_logged_in_user()
    {
        $this->load->model('user_model');

        $logged_in_user_id = $this->get_logged_in_user('id');

        $user = $this->user_model->get_user($logged_in_user_id, 'id');

        $this->session->set_userdata('logged_in_user', $user);

        return TRUE;
    }
}
