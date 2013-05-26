<?php
class Snippets extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer'));
	}
	
	function index()
	{
		$this->load->model('snippet_model');
		
		$snippets = $this->snippet_model->get_all_snippets();
		
		$this->load->view('common/header', array('title' => 'Snippets'));
		$this->load->view('snippets/index', array('snippets' => $snippets));
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->model('editor_model');
		
		$this->load->view('common/header', array('title' => 'Add Snippet'));
		
		$editor_id = $this->input->get('editor_id');
		$editor = $this->editor_model->get_editor($editor_id, 'snippet');
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		$data = array(
			'editor_list' => $editors,
			'editor_id' => $editor->id
		);
		$this->load->view('snippets/add', $data);
		
		$this->load->view('common/footer');
	}
	
	function edit($snippet_id = -1)
	{
		$this->load->model('snippet_model');
		$this->load->model('editor_model');
		
		$snippet = $this->snippet_model->get_snippet($snippet_id, 'id');
		
		if (!$snippet)
		{
			$data = array(
				'result' => array(
					'success'	=> FALSE,
					'message'	=> 'The snippet you are trying to edit could not be found, please try again, if this error persists, please contact your administrator.'
				),
				'redirect_to' => admin_url('snippets'),
				'ajax' => FALSE
			);
			
			$this->load->view('action_result', $data);
			
			return;
		}

		$this->load->view('common/header', array('title' => 'Edit Snippet'));
		
		$editor = $this->editor_model->get_editor($snippet->editor);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		
		$data = array(
			'snippet' => $snippet,
			'snippet_id' => $snippet_id,
			'editor_list' => $editors
		);
		
		$this->load->view('snippets/edit', $data);
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('snippet_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Snippet has been added successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this snippet!";
		}
		else
		{
			try
			{
				$this->snippet_model->add_snippet($alias, $title, $content, $editor);
			}
			catch (exception $e)
			{
				$success = FALSE;
				$message = $e->getMessage();
			}
		}
		
		$redirect_to = admin_url('snippets');
		if (!$success)
		{
			$redirect_to = admin_url('snippets/add');
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

	function delete($snippet_id = -1)
	{	
		$this->load->view('common/header', array('title' => 'Delete Snippet'));
		$this->load->view('snippets/delete', array('snippet_id' => $snippet_id));
		$this->load->view('common/footer');
	}
	
	function do_delete($snippet_id, $no_ajax = FALSE)
	{
		$this->load->model('snippet_model');
				
		$success = TRUE;
		$message = 'Snippet has been deleted successfully!';
		try
		{
			$this->snippet_model->delete_snippet($snippet_id);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('snippets');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		// Add snippet ID to returned data so that JS can remove the correct entry from the table
		$data['result']['snippet_id'] = $snippet_id;

		$this->load->view('action_result', $data);
	}
	
	function do_edit($no_ajax = FALSE)
	{
		$this->load->model('snippet_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$snippet_id = $this->input->post('snippet-id');
		$original_alias = $this->input->post('original-alias');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Snippet has been saved successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this snippet!";
		}
		else
		{
			try
			{
				$this->snippet_model->update_snippet($snippet_id, $original_alias, $alias, $title, $content, $editor);
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
			'redirect_to' => admin_url("snippets/edit/$snippet_id"),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
}
