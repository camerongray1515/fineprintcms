<?php
class Login extends CI_Controller {
	function index()
	{
		$this->load->view('common/top_html', array('title' => 'Login'));
		$this->load->view('login');
		$this->load->view('common/bottom_html');
	}
	
	function do_login($no_ajax = FALSE)
	{
		$this->load->model('login_model');
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		$success = $this->login_model->do_login($username, $password);
		
		$message = "Welcome to Fine Print CMS!";
		$redirect_to = admin_url();
		if (!$success)
		{
			$message = "Incorrect username/password combination, please try again";
			$redirect_to = admin_url('login');
		}
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message,
				'username'	=> $username
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
	
	function logout()
	{
		$this->load->model('login_model');
		
		$this->login_model->logout();
		
		$data = array(
			'result' => array(
				'success'	=> TRUE,
				'message'	=> 'You have been logged out successfully!',
				'username'	=> ''
			),
			'redirect_to' => admin_url('login'),
			'ajax' => FALSE
		);
		
		$this->load->view('action_result', $data);
	}
}
