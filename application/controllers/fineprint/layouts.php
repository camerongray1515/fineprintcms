<?php
class Layouts extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer'));
	}
	
	function index()
	{
		$this->load->model('layout_model');
		
		$layouts = $this->layout_model->get_all_layouts();
		
		$this->load->view('common/header', array('title' => 'Layouts'));
		$this->load->view('layouts/index', array('layouts' => $layouts));
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->model('editor_model');
		
		$this->load->view('common/header', array('title' => 'Add Layout'));
		
		$editor_id = $this->input->get('editor_id');
		$editor = $this->editor_model->get_editor($editor_id);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		$data = array(
			'editor_list' => $editors,
			'editor_id' => $editor->id
		);
		$this->load->view('layouts/add', $data);
		
		$this->load->view('common/footer');
	}
	
	function edit($layout_id = -1)
	{
		$this->load->model('layout_model');
		$this->load->model('editor_model');
		
		$layout = $this->layout_model->get_layout($layout_id, 'id');
		
		if (!$layout)
		{
			$data = array(
				'result' => array(
					'success'	=> FALSE,
					'message'	=> 'The layout you are trying to edit could not be found, please try again, if this error persists, please contact your administrator.'
				),
				'redirect_to' => admin_url('layouts'),
				'ajax' => FALSE
			);
			
			$this->load->view('action_result', $data);
			
			return;
		}

		$this->load->view('common/header', array('title' => 'Edit Layout'));
		
		$editor = $this->editor_model->get_editor($layout->editor);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		
		$data = array(
			'layout' => $layout,
			'layout_id' => $layout_id,
			'editor_list' => $editors
		);
		
		$this->load->view('layouts/edit', $data);
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('layout_model');
		
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Layout has been added successfully!';
		if (trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title for this layout!";
		}
		else
		{
			try
			{
				$this->layout_model->add_layout($title, $content, $editor);
			}
			catch (exception $e)
			{
				$success = FALSE;
				$message = $e->getMessage();
			}
		}
		
		$redirect_to = admin_url('layouts');
		if (!$success)
		{
			$redirect_to = admin_url('layouts/add');
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
			$data['result']['content'] = $content;
		}
		
		$this->load->view('action_result', $data);
	}

	function delete($layout_id = -1)
	{	
		$this->load->view('common/header', array('title' => 'Delete Layout'));
		$this->load->view('layouts/delete', array('layout_id' => $layout_id));
		$this->load->view('common/footer');
	}
	
	function do_delete($layout_id, $no_ajax = FALSE)
	{
		$this->load->model('layout_model');
				
		$success = TRUE;
		$message = 'Layout has been deleted successfully!';
		try
		{
			$this->layout_model->delete_layout($layout_id);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('layouts');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		// Add layout ID to returned data so that JS can remove the correct entry from the table
		$data['result']['layout_id'] = $layout_id;

		$this->load->view('action_result', $data);
	}
	
	function do_edit($no_ajax = FALSE)
	{
		$this->load->model('layout_model');
		
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		$layout_id = $this->input->post('layout-id');
		$original_title = $this->input->post('original-title');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Layout has been saved successfully!';
		if (trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title for this layout!";
		}
		else
		{
			try
			{
				$this->layout_model->update_layout($layout_id, $original_title, $title, $content, $editor);
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
			'redirect_to' => admin_url("layouts/edit/$layout_id"),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
}
