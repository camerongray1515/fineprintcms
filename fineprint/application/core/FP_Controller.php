<?php
class FP_Controller extends CI_Controller {
	function __construct()
	{		
		parent::__construct();
		
		$this->load->model('login_model');
		$this->load->model('user_model');
		
		$logged_in_user = $this->login_model->get_logged_in_user();
		
		$current_controller = $this->router->fetch_class();
		
		$error = FALSE;
		
		if (!$logged_in_user)
		{
			$error = 'Please login to continue.';
		}
		else if (!$this->login_model->is_allowed_to_access_controller($current_controller))
		{		
			$error = 'You do not have permission to access that area, please contact your administrator.  Either log in as a user that has permission or press the back button in your browser to go back to where you came from.';
		}
		
		if ($error)
		{
			// Not logged in so redirect
			$data = array(
			'result' => array(
				'success'	=> FALSE,
				'message'	=> $error,
				'username'	=> ''
			),
				'redirect_to' => admin_url('login'),
				'ajax' => FALSE
			);
			
			$this->load->view('action_result', $data);
			
			return;
		}
	}
}
