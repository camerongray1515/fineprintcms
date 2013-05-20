<?php
class Blocks extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer', 'user'));
	}
	
	function index()
	{
		$this->load->model('block_model');
		
		$blocks = $this->block_model->get_all_blocks();
		
		$this->load->view('common/header', array('title' => 'Blocks'));
		$this->load->view('blocks/index', array('blocks' => $blocks));
		$this->load->view('common/footer');
	}
	
	function add()
	{
		$this->load->model('editor_model');
		
		$this->load->view('common/header', array('title' => 'Add Block'));
		
		$editor_id = $this->input->get('editor_id');
		$editor = $this->editor_model->get_editor($editor_id);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		$data = array(
			'editor_list' => $editors,
			'editor_id' => $editor->id
		);
		$this->load->view('blocks/add', $data);
		
		$this->load->view('common/footer');
	}
	
	function edit($block_id = -1)
	{
		$this->load->model('block_model');
		$this->load->model('editor_model');
		
		$block = $this->block_model->get_block($block_id, 'id');
		
		if (!$block)
		{
			$data = array(
				'result' => array(
					'success'	=> FALSE,
					'message'	=> 'The block you are trying to edit could not be found, please try again, if this error persists, please contact your administrator.'
				),
				'redirect_to' => admin_url('blocks'),
				'ajax' => FALSE
			);
			
			$this->load->view('action_result', $data);
			
			return;
		}

		$this->load->view('common/header', array('title' => 'Edit Block'));
		
		$editor = $this->editor_model->get_editor($block->editor);
		$this->load->view('common/content_editor', array('editor' => $editor));
		
		$editors = $this->editor_model->editor_list();
		
		$data = array(
			'block' => $block,
			'block_id' => $block_id,
			'editor_list' => $editors
		);
		
		$this->load->view('blocks/edit', $data);
		$this->load->view('common/footer');
	}
	
	function do_add($no_ajax = FALSE)
	{
		$this->load->model('block_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Block has been added successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this block!";
		}
		else
		{
			try
			{
				$this->block_model->add_block($alias, $title, $content, $editor);
			}
			catch (exception $e)
			{
				$success = FALSE;
				$message = $e->getMessage();
			}
		}
		
		$redirect_to = admin_url('blocks');
		if (!$success)
		{
			$redirect_to = admin_url('blocks/add');
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

	function delete($block_id = -1)
	{	
		$this->load->view('common/header', array('title' => 'Delete Block'));
		$this->load->view('blocks/delete', array('block_id' => $block_id));
		$this->load->view('common/footer');
	}
	
	function do_delete($block_id, $no_ajax = FALSE)
	{
		$this->load->model('block_model');
				
		$success = TRUE;
		$message = 'Block has been deleted successfully!';
		try
		{
			$this->block_model->delete_block($block_id);
		}
		catch (exception $e)
		{
			$success = FALSE;
			$message = $e->getMessage();
		}
		
		$redirect_to = admin_url('blocks');
		
		$data = array(
			'result' => array(
				'success'	=> $success,
				'message'	=> $message
			),
			'redirect_to' => $redirect_to,
			'ajax' => !$no_ajax
		);
		
		// Add block ID to returned data so that JS can remove the correct entry from the table
		$data['result']['block_id'] = $block_id;

		$this->load->view('action_result', $data);
	}
	
	function do_edit($no_ajax = FALSE)
	{
		$this->load->model('block_model');
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$content = $this->input->post('content');
		$block_id = $this->input->post('block-id');
		$original_alias = $this->input->post('original-alias');
		$editor = $this->input->post('editor');
		
		$success = TRUE;
		$message = 'Block has been saved successfully!';
		if (trim($alias) === "" || trim($title) === "")
		{
			$success = FALSE;
			$message = "You must supply a title and alias for this block!";
		}
		else
		{
			try
			{
				$this->block_model->update_block($block_id, $original_alias, $alias, $title, $content, $editor);
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
			'redirect_to' => admin_url("blocks/edit/$block_id"),
			'ajax' => !$no_ajax
		);
		
		$this->load->view('action_result', $data);
	}
}
