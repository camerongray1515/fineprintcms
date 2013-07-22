<?php
class Main extends CI_Controller {
    function index()
    {
        redirect(admin_url('dashboard'));
    }
}