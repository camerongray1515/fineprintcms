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

        $content = $this->module->render_view('test_view', array('title' => $title, 'body' => $this->module->get_page_alias()));

        // $content = $this->module->escape_tags($content);

        return $content;
    }

    function FRONTEND_test()
    {
        return "[* core.load_block('footer') *]";
    }

    function BACKEND_index()
    {
        return "<h1>It works!</h1>";
    }

    function BACKEND_repeat()
    {
        $value = $this->FP->input->get('message');
        return "You said: $value";
    }
}