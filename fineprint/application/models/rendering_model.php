<?php
class Rendering_model extends CI_Model {
    private $page_alias = FALSE;

	function entry_point($page_alias)
	{
		$this->load->model('page_model');
		$this->load->model('layout_model');

        $this->page_alias = $page_alias;

		$page = $this->page_model->get_page($page_alias);
		
		// We start rendering from the layout
		$layout = $this->layout_model->get_layout($page->layout, 'id');

		$page = $this->render_content($layout->content);

        // Replace any escaped tags
        $page = str_replace(ESCAPED_OPEN_TAG, OPEN_TAG, $page);
        $page = str_replace(ESCAPED_CLOSE_TAG, CLOSE_TAG, $page);
		
		return $page;
	}
	
	function render_content($content)
    {
        // Take hash of content from before update
        $before_hash = hash('sha256', $content);

		// Extract function calls from content
		$get_tag_regex = TAG_REGEX;
		preg_match_all($get_tag_regex, $content, $function_calls);

        //die(print_r($function_calls, TRUE));

		$function_calls = $function_calls[1];
		
		$functions = array();
		
		// Now take each function call, split it into its components, execute it and build array of function calls to results
		foreach($function_calls as $function_call)
		{			
			$regex = "/(((?:[a-z][a-z0-9_]*))\\.)?((?:[a-z][a-z0-9_]*))(\(.*\))/is";
			preg_match_all($regex, $function_call, $parts);
			
			$function = new stdClass();
			$function->original_call = $parts[0][0];
			$function->module = (empty($parts[2][0])) ? 'core': $parts[2][0]; // If module is not specified, add implied "core"
			$function->function = $parts[3][0];
			$function->parameters = preg_split('/,(?=([^\"]*\"[^\"]*\")*[^\"]*$)/', str_replace("'", '"', trim($parts[4][0], '()'))); // Isolate individual parameters into array
			
			// Go through the parameters and remove the quotes surrounding the string
			array_walk($function->parameters, function(&$value) {
				$value = str_replace('"', '', $value);
			});
			
			$function->result = $this->execute_function($function);
			
			array_push($functions, $function);
		}
		
		// Now replace all tags with their retrieved content
		foreach ($functions as $function)
		{
			$replace_tag_regex = '/' . preg_quote(OPEN_TAG, '/') . '[ \t]*' . preg_quote($function->original_call, '/') . '[ \t]*' . preg_quote(CLOSE_TAG, '/') . '/';
			$content = preg_replace($replace_tag_regex, $function->result, $content);
		}

        $after_hash = hash('sha256', $content);

        if ($before_hash == $after_hash)
        {
            die('<h1>Fatal Error</h1><p>It appears as though Fine Print CMS encountered an infinite loop when rendering this page.</p>');
        }

        // If there are still functions, render again
		preg_match_all($get_tag_regex, $content, $function_calls);
		$function_calls = $function_calls[1];
		if (count($function_calls) != 0)
		{
			$content = $this->render_content($content);
		}

		return $content;
	}

	function execute_function($function)
	{
        $this->load->model('module_model');

        $result = '';
        try
        {
            $result = $this->module_model->execute($function->module, 'tag', $function->function, $this->page_alias, $function->parameters);
        }
        catch (exception $e)
        {
            $error = $e->getMessage();

            $error = str_replace('class', 'module', $error); // Get the language right to avoid confusion!

            $result = "<!-- Error: $error -->";
        }

        return $result;
	}

//    function execute_function($function)
//	{
//		$result = "";
//		try
//		{
//            if (file_exists(APPPATH."libraries/modules/{$function->module}.php"))
//            {
//                $this->load->library("modules/{$function->module}");
//            }
//            else if (file_exists(APPPATH."../../modules/{$function->module}/{$function->module}.php"))
//            {
//                $this->load->library("../../../modules/{$function->module}/{$function->module}");
//            }
//            else
//            {
//                throw new Exception("Unable to load the requested module ({$function->module})", 1);
//            }
//
//			if (method_exists($function->module, 'TAG_' . $function->function))
//			{
//				$result = $this->{$function->module}->{'TAG_'. $function->function}($function->parameters);
//			}
//			else
//			{
//				throw new Exception("The module you are calling does not contain the function: {$function->function}", 1);
//			}
//		}
//		catch (exception $e)
//		{
//			$error = $e->getMessage();
//
//			$error = str_replace('class', 'module', $error); // Get the language right to avoid confusion!
//
//			$result = "<!-- Error: $error -->";
//		}
//
//		return $result;
//	}
}
