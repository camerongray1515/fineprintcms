<?php
class Snippets extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer'));
	}
	
	function index()
	{
		$this->load->model('snippets_model');
		
		$snippets = $this->snippets_model->get_all_snippets();
		
		$this->load->view('common/header', array('title' => 'Snippets'));
		$this->load->view('snippets/index', array('snippets' => $snippets));
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->view('common/header', array('title' => 'Add Snippet'));
		$this->load->view('common/content_editor');
		$this->load->view('snippets/add');
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('snippets_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		
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
				$this->snippets_model->add_snippet($alias, $title, $content);
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
		$this->load->model('snippets_model');
				
		$success = TRUE;
		$message = 'Snippet has been deleted successfully!';
		try
		{
			$this->snippets_model->delete_snippet($snippet_id);
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
	
	function edit($snippet_id = -1)
	{
		$this->load->model('snippets_model');
		
		$snippet = $this->snippets_model->get_snippet($snippet_id, 'id');
		
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
		
		$data = array(
			'snippet' => $snippet,
			'snippet_id' => $snippet_id
		);
		
		$this->load->view('common/header', array('title' => 'Edit Snippet'));
		$this->load->view('common/content_editor');
		$this->load->view('snippets/edit', $data);
		$this->load->view('common/footer');
	}
	
	function do_edit($no_ajax)
	{
		$this->load->model('snippets_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$snippet_id = $this->input->post('snippet_id');
		$original_alias = $this->input->post('original_alias');
		
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
				$this->snippets_model->update_snippet($snippet_id, $original_alias, $alias, $title, $content);
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
