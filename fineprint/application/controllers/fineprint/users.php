<?php
class Users extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator'));
	}
	
	function index()
	{
		$this->load->model('user_model');
		
		$data['users'] = $this->user_model->get_all_users();
		
		$this->load->view('common/header', array('title' => 'Site Settings'));
		$this->load->view('users/index', $data);
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->view('common/header', array('title' => 'Site Settings'));
		$this->load->view('users/add');
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('user_model');
		
		$first_name = $this->input->post('first-name');
		$last_name = $this->input->post('last-name');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$role = $this->input->post('role');
		
		$password = $this->input->post('password');
		$password_confirm = $this->input->post('password-confirm');
		
		$success = TRUE;
		$message = 'User has been added successfully!';
		
		if (trim($username) == '' || trim($password) == '' || trim($first_name) == '')
		{
			$success = FALSE;
			$message = 'You must supply, at least, a first name, username and password for this user.';
		}
		else if ($password != $password_confirm)
		{
			$success = FALSE;
			$message = 'Passwords do not match, please try again!';
		}
		else
		{
			// Now actually add the user
			try
			{
				$this->user_model->add_user($first_name, $last_name, $username, $email, $password, $role);
			}
			catch (exception $e)
			{
				$success = FALSE;
				$message = $e->getMessage();
			}
		}
				
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => ($success) ? admin_url('users') : admin_url('users/add'),
			'ajax' => !$no_ajax
		);
		
		// Add some of the supplied data to the returned array so it can be reinserted into form fields in the event of an error
		if ($no_ajax)
		{
			$data['result']['first_name'] = $first_name;
			$data['result']['last_name'] = $last_name;
			$data['result']['email'] = $email;
			$data['result']['username'] = $username;
			$data['result']['role'] = $role;
		}
		
		$this->load->view('action_result', $data);
	}
}
