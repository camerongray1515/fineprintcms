<?php
class Main extends CI_Controller {
	function index() {
		$this->load->model('rendering_model');
		$this->load->model('page_model');
		$this->load->model('setting_model');
						
		if (uri_string() == "")
		{
			// Retrieve the alias of the home page
			$GLOBALS['page_alias'] = $this->setting_model->get('home_page');
		}
		else
		{
			$GLOBALS['page_alias'] = uri_string();
		}
		
		// Now check if the page exists, if it doesn't then we want to render the 404 page
		if (!$this->page_model->get_page($GLOBALS['page_alias'], 'alias'))
		{
			$GLOBALS['page_alias'] = $this->setting_model->get('404_page');
		}
		
		$page = $this->rendering_model->entry_point($GLOBALS['page_alias']);
		
		echo $page;
	}
}
