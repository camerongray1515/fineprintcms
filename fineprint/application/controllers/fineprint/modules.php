<?php
class Modules extends FP_Controller {
    function index()
    {
        $this->load->model('module_model');

        $this->module_model->scan_modules();

        $data['modules'] = $this->module_model->get_all();

        $this->load->view('common/header', array('title' => 'Modules'));
        $this->load->view('modules/index', $data);
        $this->load->view('common/footer');
    }

    function module($module_identifier = FALSE, $function = 'index')
    {
        $this->load->model('module_model');

        $this->load->view('common/header', array('title' => 'Modules'));
        $data['content'] = '';
        try
        {
            $data['content'] = $this->module_model->execute($module_identifier, 'backend', $function);
        }
        catch (Exception $e)
        {
            $data['content'] = $e->getMessage();
        }
        $this->load->view('modules/container', $data);
        $this->load->view('common/footer');
    }
}