<?php
class Core extends Module {
	// Parameters: snippet_alias
	function TAG_load_snippet($parameters)
	{
		$this->FP->load->model('snippet_model');
		
		$snippet = $this->FP->snippet_model->get_snippet($parameters[0], 'alias');
	
		return $snippet->content;
	}
	
	// Parameters: block_alias
	function TAG_load_block($parameters)
	{
		$this->FP->load->model('block_model');
		
		$block = $this->FP->block_model->get_block($parameters[0], 'alias');
	
		return $block->content;
	}
	
	function get_page()
	{
		if (!$this->module->get_module_data('core', 'page'))
		{
			$this->FP->load->model('page_model');
			
			$page = $this->FP->page_model->get_page($GLOBALS['page_alias'], 'alias');
			
			$this->module->set_module_data('core', 'page', $page);
		}
				
		return $this->module->get_module_data('core', 'page');
	}
	
	// Parameters: [NONE]
	function TAG_load_page_content()
	{
		$page = $this->get_page();
		
		return $page->content;
	}
	
	// Parameters: date_string
	function TAG_get_date($parameters)
	{
		return date($parameters[0]);
	}
	
	// Parameters: [NONE]
	function TAG_current_page_title()
	{
		$page = $this->get_page();
		
		return $page->title;
	}
	
	// Parameters: path
	function TAG_base_url($parameters)
	{
		$path = $parameters[0];
		
		return base_url($path);
	}
	
	// Parameters: path
	function TAG_site_url($parameters)
	{
		$path = $parameters[0];
		
		return site_url($path);
	}

    // Parameters: path
    function TAG_admin_url($parameters)
    {
        $path = $parameters[0];

        return admin_url($path);
    }
}
