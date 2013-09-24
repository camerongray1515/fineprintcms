<?php
class Module {
    protected $FP;
    protected $module;
    protected $module_path;

    function __construct()
    {
        $this->FP =& get_instance();
    }

    function construct_module_methods($module_path, $page_alias)
    {
        $this->module = new Module_methods($page_alias);
        $this->module->path = $module_path;
    }
}

class Module_methods {
    private $module_data_store = array();
    private $page_alias;

    public $path = '';

    function __construct($page_alias)
    {
        $this->page_alias = $page_alias;
    }

    function set_module_data($module, $key, $value)
    {
        $this->module_data_store[$module][$key] = $value;
    }

    function get_module_data($module, $key)
    {
        if (isset($this->module_data_store[$module][$key]))
        {
            return $this->module_data_store[$module][$key];
        }

        return FALSE;
    }

    function get_page_alias()
    {
        return $this->page_alias;
    }

    function render_view($view, $variables = array())
    {
        $view = str_replace('.', '', $view);

        // Convert $variables (array) into individual variables
        extract($variables);

        ob_start();
        include "{$this->path}/views/$view.php";
        return ob_get_clean();
    }

    function get_directory()
    {
        // Use the debug backtrace to find what directory the calling module is in

        $backtrace = debug_backtrace();

        $highest_file = '';
        foreach ($backtrace as $entry)
        {
            if (strpos($entry['file'], 'rendering_model.php') === FALSE)
            {
                $highest_file = $entry['file'];
            }
            else
            {
                break;
            }
        }

        return dirname($highest_file);
    }

    function escape_tags($content)
    {
        $content = str_replace(OPEN_TAG, ESCAPED_OPEN_TAG, $content);
        $content = str_replace(CLOSE_TAG, ESCAPED_CLOSE_TAG, $content);

        return $content;
    }
}