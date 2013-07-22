<?php
class Users extends FP_Controller {
	function index()
	{
		$this->load->model('user_model');
		
		$data['users'] = $this->user_model->get_all_users();
		
		$this->load->view('common/header', array('title' => 'Users'));
		$this->load->view('users/index', $data);
		$this->load->view('common/footer');
	}
	
	function add()
	{
        $this->load->model('roles_model');

        $roles = $this->roles_model->get_all_roles();

		$this->load->view('common/header', array('title' => 'Add User'));
		$this->load->view('users/add', array('roles' => $roles));
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

    function check_username()
    {
        $this->load->model('user_model');

        $username = $this->input->post('username');

        $user_exists = $this->user_model->user_exists($username);

        $data['result']['username_in_use'] = FALSE;
        if ($user_exists)
        {
            $data['result']['username_in_use'] = TRUE;
        }

        $data['ajax'] = TRUE;
        $data['redirect_to'] = FALSE;

        $this->load->view('action_result', $data);
    }

    function delete($user_id)
    {
        $data['user_id'] = $user_id;

        $this->load->view('common/header', array('title' => 'Delete User'));
        $this->load->view('users/delete', $data);
        $this->load->view('common/footer');
    }

    function do_delete($user_id, $no_ajax = FALSE)
    {
        $this->load->model('user_model');

        $success = TRUE;
        $message = 'User has been deleted successfully!';
        try
        {
            $this->user_model->disable_user($user_id);
        }
        catch (Exception $e)
        {
            $success = FALSE;
            $message = $e->getMessage();
        }

        $data = array(
            'result' => array(
                'success'	=> $success,
                'message'	=> $message
            ),
            'redirect_to' => admin_url('users'),
            'ajax' => !$no_ajax
        );

        $this->load->view('action_result', $data);
    }

    function edit($user_id = FALSE)
    {
        $this->load->model('roles_model');
        $this->load->model('user_model');

        $data['roles'] = $this->roles_model->get_all_roles();
        $data['user'] = $this->user_model->get_user($user_id);

        $this->load->view('common/header', array('title' => 'Edit User'));
        $this->load->view('users/edit', $data);
        $this->load->view('common/footer');
    }

    function do_edit($no_ajax = FALSE)
    {
        $this->load->model('user_model');

        $first_name = $this->input->post('first-name');
        $last_name = $this->input->post('last-name');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $role = $this->input->post('role');
        $original_username = $this->input->post('original-username');
        $user_id = $this->input->post('user-id');

        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password-confirm');

        $success = TRUE;
        $message = 'User has been added successfully!';

        if (trim($username) == '' || trim($first_name) == '')
        {
            $success = FALSE;
            $message = 'You must supply, at least, a first name and username for this user.';
        }
        else
        {
            // Now actually save the user
            try
            {
                $this->user_model->update_user($user_id, $original_username, $first_name, $last_name, $username, $email, $role);

                // If new passwords have been supplied, update the password too
                if (trim($password))
                {
                    $this->user_model->update_password($user_id, $password, $password_confirm);
                }
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
            $data['result']['original_username'] = $original_username;
        }

        $this->load->view('action_result', $data);
    }
}
