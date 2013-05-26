<?php
class Pages extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer', 'user'));
	}
	
	function index()
	{
		$this->load->model('page_model');
		
		$pages = $this->page_model->get_all_pages();
		
		$this->load->view('common/header', array('title' => 'Pages'));
		$this->load->view('pages/index', array('pages' => $pages));
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->model('editor_model');
		$this->load->model('layout_model');
		
		$this->load->view('common/header', array('title' => 'Add Page'));
		
		$editor_id = $this->input->get('editor_id');
		$editor = $this->editor_model->get_editor($editor_id, 'page');
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$layouts = $this->layout_model->get_all_layouts();
		$editors = $this->editor_model->editor_list();
		$data = array(
			'editor_list' => $editors,
			'editor_id' => $editor->id,
			'layouts' => $layouts
		);
		$this->load->view('pages/add', $data);
		
		$this->load->view('common/footer');
	}
	
	function edit($page_id = -1)
	{
		$this->load->model('page_model');
		$this->load->model('editor_model');
		$this->load->model('layout_model');
		
		$page = $this->page_model->get_page($page_id, 'id');
		
		if (!$page)
		{
			$data = array(
				'result' => array(
					'success'	=> FALSE,
					'message'	=> 'The page you are trying to edit could not be found, please try again, if this error persists, please contact your administrator.'
				),
				'redirect_to' => admin_url('pages'),
				'ajax' => FALSE
			);
			
			$this->load->view('action_result', $data);
			
			return;
		}

		$this->load->view('common/header', array('title' => 'Edit Page'));
		
		$editor = $this->editor_model->get_editor($page->editor);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$layouts = $this->layout_model->get_all_layouts();
		$editors = $this->editor_model->editor_list();
		$data = array(
			'page' => $page,
			'page_id' => $page_id,
			'editor_list' => $editors,
			'layouts' => $layouts
		);
		
		$this->load->view('pages/edit', $data);
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('page_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$editor = $this->input->post('editor');
		$layout_id = $this->input->post('layout');
		
		$success = TRUE;
		$message = 'Page has been added successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this page!";
		}
		else
		{
			try
			{
				$this->page_model->add_page($alias, $title, $layout_id, $content, $editor);
			}
			catch (exception $e)
			{
				$success = FALSE;
				$message = $e->getMessage();
			}
		}
		
		$redirect_to = admin_url('pages');
		if (!$success)
		{
			$redirect_to = admin_url('pages/add');
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
			$data['result']['title'] = $title;
			$data['result']['alias'] = $alias;
			$data['result']['content'] = $content;
		}
		
		$this->load->view('action_result', $data);
	}

	function delete($page_id = -1)
	{	
		$this->load->view('common/header', array('title' => 'Delete Page'));
		$this->load->view('pages/delete', array('page_id' => $page_id));
		$this->load->view('common/footer');
	}
	
	function do_delete($page_id, $no_ajax = FALSE)
	{
		$this->load->model('page_model');
				
		$success = TRUE;
		$message = 'Page has been deleted successfully!';
		try
		{
			$this->page_model->delete_page($page_id);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('pages');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		// Add page ID to returned data so that JS can remove the correct entry from the table
		$data['result']['page_id'] = $page_id;

		$this->load->view('action_result', $data);
	}
	
	function do_edit($no_ajax = FALSE)
	{
		$this->load->model('page_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$page_id = $this->input->post('page-id');
		$original_alias = $this->input->post('original-alias');
		$editor = $this->input->post('editor');
		$layout_id = $this->input->post('layout');
		
		$success = TRUE;
		$message = 'Page has been saved successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this page!";
		}
		else
		{
			try
			{
				$this->page_model->update_page($page_id, $original_alias, $alias, $title, $layout_id, $content, $editor);
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
			'redirect_to' => admin_url("pages/edit/$page_id"),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
}
