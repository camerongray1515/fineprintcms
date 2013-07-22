<?php
class Roles extends FP_Controller {
    function index()
    {
        $this->load->model('roles_model');

        $data['controllers'] = $this->roles_model->get_all_controllers();
        $data['roles'] = $this->roles_model->get_role_translations();
        $data['roles_table'] = $this->roles_model->get_data_for_table();

        $this->load->view('common/header', array('title' => 'Roles & Permissions'));
        $this->load->view('settings/common/top');
        $this->load->view('roles/index', $data);
        $this->load->view('common/footer');
    }

    function add()
    {
        $this->load->model('roles_model');

        $data['controllers'] = $this->roles_model->get_all_controllers();

        $this->load->view('common/header', array('title' => 'Add Role'));
        $this->load->view('settings/common/top');
        $this->load->view('roles/add', $data);
        $this->load->view('common/footer');
    }

    function edit($role_id)
    {
        $this->load->model('roles_model');

        $data['role'] = $this->roles_model->get_full_role($role_id);

        $this->load->view('common/header', array('title' => 'Edit Role'));
        $this->load->view('settings/common/top');
        $this->load->view('roles/edit', $data);
        $this->load->view('common/footer');
    }

    function do_add($no_ajax = FALSE)
    {
        $this->load->model('roles_model');

        $name = $this->input->post('name');
        $allowed_controllers = $this->input->post('controllers');

        $success = TRUE;
        $message = 'Role has been added successfully!';
        try
        {
            $this->roles_model->add_role($name, $allowed_controllers);
        }
        catch (Exception $e)
        {
            $success = FALSE;
            $message = $e->getMessage();
        }

        $redirect_to = admin_url('roles');
        if (!$success)
        {
            $redirect_to = admin_url('roles/add');
        }

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
            $data['result']['name'] = $name;
            $data['result']['allowed_controllers'] = $allowed_controllers;
        }

        $this->load->view('action_result', $data);
    }

    function do_edit($no_ajax = FALSE)
    {
        $this->load->model('roles_model');

        $name = $this->input->post('name');
        $allowed_controllers = $this->input->post('controllers');
        $role_id = $this->input->post('role-id');

        $success = TRUE;
        $message = 'Role has been saved successfully!';
        try
        {
            $this->roles_model->edit_role($role_id, $name, $allowed_controllers);
        }
        catch (Exception $e)
        {
            $success = FALSE;
            $message = $e->getMessage();
        }

        $redirect_to = admin_url('roles');
        if (!$success)
        {
            $redirect_to = admin_url('roles/add');
        }

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
            $data['result']['name'] = $name;
            $data['result']['allowed_controllers'] = $allowed_controllers;
        }

        $this->load->view('action_result', $data);
    }

    function delete($role_id)
    {
        $data['role_id'] = $role_id;

        $this->load->view('common/header', array('title' => 'Delete Role'));
        $this->load->view('settings/common/top');
        $this->load->view('roles/delete', $data);
        $this->load->view('common/footer');
    }

    function do_delete($role_id, $no_ajax = FALSE)
    {
        $this->load->model('roles_model');

        $success = TRUE;
        $message = 'Role has been deleted successfully!';
        try
        {
            $this->roles_model->delete_role($role_id);
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
            'redirect_to' => admin_url('roles'),
            'ajax' => !$no_ajax
        );

        $this->load->view('action_result', $data);
    }
}