<?php
class Module_frontend extends CI_Controller {
    function index($module_identifier = FALSE, $function = FALSE)
    {
        $this->load->model('module_model');

        try
        {
            $result = $this->module_model->execute($module_identifier, 'frontend', $function);
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
        }

        echo $result;
    }
}