<?php
class FP_Controller extends CI_Controller {
	function __construct($allowed_roles = array())
	{
		parent::__construct();
		
		$this->load->model('login_model');
		
		$logged_in_user = $this->login_model->get_logged_in_user();
		
		if (!$logged_in_user || array_search($logged_in_user->role, $allowed_roles) === FALSE)
		{		
			// Not logged in so redirect
			$data = array(
			'result' => array(
				'success'	=> FALSE,
				'message'	=> 'You do not have permission to access that area, please login to continue.',
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
