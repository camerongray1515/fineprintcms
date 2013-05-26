<?php
// This controller is used to allow Javascript to interract with some of Codeigniter's functions
class Javascript extends CI_Controller {

        function index()
        {
                header("Content-type: text/javascript");

                echo "var ci = {
	                	site_url: '" . site_url() . "',
	                	base_url: '" . application_base_url() . "',
	                	admin_url: '" . admin_url() . "'
                	};";
        }
		
		function set_flashdata_message()
		{
			$success = $this->input->post('success');
			$message = $this->input->post('message');
			
			$this->session->set_flashdata('success', $success);
			$this->session->set_flashdata('message', $message);
		}
}