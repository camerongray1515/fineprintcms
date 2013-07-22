<?php
class Dashboard extends FP_Controller {	
	function index()
	{
        $this->load->model('rendering_model');

        // Render the dashboard by passing a single tag into the regular rendering model
        $data['content'] = $this->rendering_model->render_content(OPEN_TAG . ' admin.render_dashboard() ' . CLOSE_TAG);

		$this->load->view('common/header', array('title' => 'Dashboard'));
		$this->load->view('dashboard/main', $data);
		$this->load->view('common/footer');
	}
}
