<?php
class Module_model extends CI_Model {
    function execute($module, $type, $function, $parameters = FALSE)
    {
        $this->load->library('module');

        $module = str_replace('.', '', $module);
        $function = str_replace('.', '', $function);

        $function_prefix = strtoupper($type) . '_';

        $result = "";
        if (file_exists(APPPATH."libraries/modules/{$module}.php"))
        {
            $this->load->library("modules/{$module}");
        }
        else if (file_exists(APPPATH."../../modules/{$module}/{$module}.php"))
        {
            $this->load->library("../../../modules/{$module}/{$module}");
        }
        else
        {
            throw new Exception("Unable to load the requested module ({$module})", 1);
        }

        if (method_exists($module, $function_prefix . $function))
        {
            $result = $this->{$module}->{$function_prefix . $function}($parameters);
        }
        else
        {
            throw new Exception("The module you are calling does not contain the function: {$function}", 1);
        }

        return $result;
    }

    function get_all()
    {
        $database_response = $this->db->get('modules');

        $modules = $database_response->result();

        return $modules;
    }

    function scan_modules()
    {
        $module_directory = APPPATH."../../modules/";

        $handle = opendir($module_directory);

        $modules = array();

        while (($item = readdir($handle)) !== FALSE)
        {
            // If this is not an actual directory, then skip
            if (strpos($item, '.') !== FALSE)
            {
                continue;
            }

            // Now read the 'info.json' file to get module details
            $module_json = file_get_contents($module_directory . $item .'/info.json');

            if (!$module_json)
            {
                // Module does not contain an info file so skip it
                continue;
            }

            $module = json_decode($module_json);

            array_push($modules, $module);
        }

        closedir($handle);

        // TODO: Work out what modules are to be deleted, inserted, updated or left alone then act accordingly

        return TRUE;
    }
}