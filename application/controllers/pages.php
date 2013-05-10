<?php
class Pages extends CI_Controller {
	function index() {
		echo 'Loading page: ' . uri_string();
	}
}
