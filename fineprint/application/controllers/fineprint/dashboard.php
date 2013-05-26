<?php
class Dashboard extends FP_Controller {
	function __construct()
	{
		parent::__construct(array('administrator', 'designer', 'user'));
	}
	
	function index()
	{
		$this->load->view('common/header', array('title' => 'Dashboard'));
		
		$this->load->view('common/footer');
	}
}
