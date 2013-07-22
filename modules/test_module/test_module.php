<?php
class Test_module extends Module {
    function TAG_set_data()
    {
        $this->module->set_module_data('foo', 'bar', 'baz');
    }

    function TAG_get_data()
    {
        return $this->module->get_module_data('foo', 'bar');
    }

    function TAG_view($parameters)
    {
        $title = $parameters[0];
        $content = $parameters[1];

        return $this->module->render_view('test_view', array('title' => $title, 'body' => $content));
    }

    function FRONTEND_test()
    {
        return print_r($_GET, TRUE);
    }

    function BACKEND_index()
    {
        return "<h1>It works!</h1>";
    }
}