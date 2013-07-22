<?php
class Module {
    protected $FP;
    protected $module;

    function __construct()
    {
        $this->FP =& get_instance();
        $this->module = new Module_methods();
    }
}

class Module_methods {
    private $module_data_store = array();

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

    function render_view($view, $variables)
    {
        $view = str_replace('.', '', $view);

        // Convert $variables (array) into individual variables
        extract($variables);

        ob_start();
        include $this->get_directory() . "/views/$view.php";
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
}