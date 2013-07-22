<?php
class Profile extends FP_Controller {	
	function edit()
	{
		$this->load->model('login_model');
		
		$logged_in_user = $this->login_model->get_logged_in_user();
		
		$this->load->view('common/header', array('title' => 'Edit Profile'));
		$this->load->view('profile/edit', array('user' => $logged_in_user));
		$this->load->view('common/footer');
	}
	
	function do_update_password($no_ajax = FALSE)
	{
		$this->load->model('user_model');
        $this->load->model('login_model');
		
		$current_password = $this->input->post('current-password');
		$new_password = $this->input->post('new-password');
		$confirm_new_password = $this->input->post('confirm-new-password');

        // Get the currently logged in user
        $user_id = $this->login_model->get_logged_in_user('id');
		
		$success = TRUE;
		$message = 'Password has been updated successfully!';
		try
		{
			$this->user_model->update_profile_password($user_id, $current_password, $new_password, $confirm_new_password);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('profile/edit');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
	
	function do_update_information($no_ajax = FALSE)
	{
		$this->load->model('user_model');
		$this->load->model('login_model');
		
		$first_name = $this->input->post('first-name');
		$last_name = $this->input->post('last-name');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
        $original_username = $this->input->post('original-username');
		
		// Get the currently logged in user
		$user_id = $this->login_model->get_logged_in_user('id');
		
		$success = TRUE;
		$message = 'Information has been updated successfully!';
		try
		{
			$this->user_model->update_user($user_id, $original_username, $first_name, $last_name, $username, $email);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('profile/edit');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		// If there is an error, add the form data to the returned array
		if (!$success)
		{
			$data['result']['first_name'] = $first_name;
			$data['result']['last_name'] = $last_name;
			$data['result']['username'] = $username;
			$data['result']['email'] = $email;
		}
		
		$this->load->view('action_result', $data);
	}
}
