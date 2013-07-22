<?php
class Admin extends Module {
    function __construct()
    {
        parent::__construct();

        // If not logged in, then die!
        $this->FP->load->model('login_model');
        if (!$this->FP->login_model->is_logged_in())
        {
            throw new Exception('Module can only be called when a backend user is logged in!', 1);
        }
    }

    function TAG_render_dashboard()
    {
        $this->FP->load->model('setting_model');

        $dashboard_mode = $this->FP->setting_model->get('dashboard_mode');

        $content = '';
        if ($dashboard_mode == 'iframe')
        {
            $iframe_url = $this->FP->setting_model->get('dashboard_frame_url');

            $content = $this->FP->load->view('dashboard/iframe', array('iframe_url' => $iframe_url), TRUE);
        }
        else if ($dashboard_mode == 'static')
        {
            $content = $this->FP->setting_model->get('dashboard_static_content');
        }

        return $content;
    }
}