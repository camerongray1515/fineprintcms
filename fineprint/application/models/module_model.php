<?php
class Module_model extends CI_Model {
    function execute($module, $type, $function, $page_alias = FALSE, $parameters = FALSE)
    {
        $this->load->library('module');

        $module = str_replace('.', '', $module);
        $function = str_replace('.', '', $function);

        $function_prefix = strtoupper($type) . '_';

        $result = "";
        if (file_exists(APPPATH."libraries/modules/{$module}.php"))
        {
            $this->load->library("modules/{$module}");
            $this->{$module}->construct_module_methods(APPPATH."libraries/modules", $page_alias);
        }
        else if (file_exists(APPPATH."../../modules/{$module}/{$module}.php"))
        {
            $this->load->library("../../../modules/{$module}/{$module}");
            $this->{$module}->construct_module_methods(APPPATH."../../modules/{$module}", $page_alias);
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
        $module_directory = APPPATH."../../modules/";

        $handle = opendir($module_directory);

        $installed_modules = array();

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

            array_push($installed_modules, $module);
        }

        closedir($handle);

        return $installed_modules;
    }

//    function get_all()
//    {
//        $database_response = $this->db->get('modules');
//
//        $modules = $database_response->result();
//
//        return $modules;
//    }
//
//    function scan_modules()
//    {
//        $module_directory = APPPATH."../../modules/";
//
//        $handle = opendir($module_directory);
//
//        $modules_present = array();
//
//        while (($item = readdir($handle)) !== FALSE)
//        {
//            // If this is not an actual directory, then skip
//            if (strpos($item, '.') !== FALSE)
//            {
//                continue;
//            }
//
//            // Now read the 'info.json' file to get module details
//            $module_json = file_get_contents($module_directory . $item .'/info.json');
//
//            if (!$module_json)
//            {
//                // Module does not contain an info file so skip it
//                continue;
//            }
//
//            $module = json_decode($module_json);
//
//            array_push($modules_present, $module);
//        }
//
//        closedir($handle);
//
//        $modules_to_remove = array();
//        $modules_to_add = array();
//        $modules_to_update = array();
//
//        // Get all modules
//        $installed_modules = $this->get_all();
//
//        foreach ($modules_present as $module)
//        {
//            // If module is not installed, then add it to list to be installed
//            if ($this->module_exists_in_list($installed_modules, $module->identifier) === FALSE)
//            {
//                array_push($modules_to_add, $module);
//            }
//            else
//            {
//                // If the module on disk has changed, then mark for updating
//                $module_index = $this->module_exists_in_list($installed_modules, $module->identifier);
//                $installed_module = $installed_modules[$module_index];
//
//                // if ($module->name)
//            }
//        }
//
//        print_r($modules_to_add);
//
//
//        die();
//
//        return TRUE;
//    }
//
//    private function module_exists_in_list($list, $module_identifier)
//    {
//        foreach ($list as $i=>$item)
//        {
//            if ($item->identifier == $module_identifier)
//            {
//                return $i;
//            }
//        }
//
//        return FALSE;
//    }
}