<?php
class Settings extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator'));
	}
	
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
				'pages'		=> $this->setting_model->get('default_editor_page'),
				'blocks'		=> $this->setting_model->get('default_editor_block'),
				'layouts'		=> $this->setting_model->get('default_editor_layout'),
				'snippets'	=> $this->setting_model->get('default_editor_snippet')
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
			'page'		=> $this->input->post('editor-pages'),
			'block'		=> $this->input->post('editor-blocks'),
			'snippet'	=> $this->input->post('editor-snippets'),
			'layout'	=> $this->input->post('editor-layouts')
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
}
