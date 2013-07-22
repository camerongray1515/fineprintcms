<?php
class Settings extends FP_Controller {
	function index()
	{
		$this->load->model('page_model');
		$this->load->model('setting_model');
		$this->load->model('editor_model');
		
		$data = array(
			'pages'				=> $this->page_model->get_all_pages(),
			'home_page'			=> $this->setting_model->get('home_page'),
			'error_404_page'	=> $this->setting_model->get('404_page')	
		);
		
		$this->load->view('common/header', array('title' => 'Site Settings'));
		$this->load->view('settings/common/top');
		$this->load->view('settings/index', $data);
		$this->load->view('common/footer');
	}
	
	function default_editors()
	{
		$this->load->model('setting_model');
		$this->load->model('editor_model');
		
		$data = array(
			'editors'			=> $this->editor_model->editor_list(),
			'default_editors'	=> array(
                'pages'		        => $this->setting_model->get('default_editor_page'),
                'blocks'		    => $this->setting_model->get('default_editor_block'),
                'layouts'	   	    => $this->setting_model->get('default_editor_layout'),
                'snippets'	        => $this->setting_model->get('default_editor_snippet'),
                'dashboard_settings'=> $this->setting_model->get('default_editor_dashboard_settings')
            )
		);
		
		$this->load->view('common/header', array('title' => 'Site Settings'));
		$this->load->view('settings/common/top');
		$this->load->view('settings/default_editors', $data);
		$this->load->view('common/footer');
	}
	
	function site_settings_do_save($no_ajax = FALSE)
	{
		$this->load->model('setting_model');
		
		$data = array(
			'home_page'	=> $this->input->post('home-page'),
			'404_page'	=> $this->input->post('404-page')
		);


		$this->setting_model->batch_update($data);
		
		$data = array(
			'result' => array(
				'success'	=> TRUE,
				'message'	=> 'Settings have been saved successfully!'
			),
			'redirect_to' => admin_url('settings'),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
	
	function default_editors_do_save($no_ajax = FALSE)
	{
		$this->load->model('setting_model');
		
		$default_editors = array(
			'page'		        => $this->input->post('editor-pages'),
			'block'		        => $this->input->post('editor-blocks'),
			'snippet'	        => $this->input->post('editor-snippets'),
			'layout'	        => $this->input->post('editor-layouts'),
			'dashboard_settings'=> $this->input->post('editor-dashboard_settings')
		);
		
		// Loop through and update settings
		foreach ($default_editors as $area => $value)
		{
			$this->setting_model->update("default_editor_$area", $value);
		}
		
		$data = array(
			'result' => array(
				'success'	=> TRUE,
				'message'	=> 'Default editors have been updated successfully!'
			),
			'redirect_to' => admin_url('settings/default_editors'),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}

    function dashboard_settings()
    {
        $this->load->model('setting_model');
        $this->load->model('editor_model');

        $editor_id = $this->input->get('editor_id');
        $editor = $this->editor_model->get_editor($editor_id, 'dashboard_settings');
        $this->load->view('common/content_editor', array('editor' => $editor));

        $editors = $this->editor_model->editor_list();

        $data = array(
            'frame_url'     => $this->setting_model->get('dashboard_frame_url'),
            'static_content'=> $this->setting_model->get('dashboard_static_content'),
            'mode'          => $this->setting_model->get('dashboard_mode'),
            'editor_list'   => $editors,
            'editor_id'     => $editor->id
        );

        $this->load->view('common/header', array('title' => 'Dashboard Settings'));
        $this->load->view('settings/common/top');
        $this->load->view('settings/dashboard', $data);
        $this->load->view('common/footer');
    }

    function dashboard_settings_do_save($no_ajax = FALSE)
    {
        $this->load->model('setting_model');

        $data = array(
            'dashboard_frame_url'     => $this->input->post('frame-url'),
            'dashboard_static_content'=> $this->input->post('content'),
            'dashboard_mode'          => $this->input->post('dashboard-type'),
        );

        $success = TRUE;
        $message = 'Settings have been saved successfully!';
        if ($data['dashboard_mode'] == 'iframe' && filter_var($data['dashboard_frame_url'], FILTER_VALIDATE_URL) === FALSE)
        {
            $success = FALSE;
            $message = 'Invalid frame URL!';
        }

        if ($data['dashboard_mode'] != 'static' && $data['dashboard_mode'] != 'iframe')
        {
            $success = FALSE;
            $message = 'Invalid dashboard mode!';
        }

        if ($success)
        {
            $this->setting_model->batch_update($data);
        }

        $data = array(
            'result' => array(
                'success'	=> $success,
                'message'	=> $message
            ),
            'redirect_to' => admin_url('settings/dashboard_settings'),
            'ajax' => !$no_ajax
        );

        $this->load->view('action_result', $data);
    }
}
