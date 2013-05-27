<?php
class Rendering_model extends CI_Model {
	function entry_point($page_alias)
	{
		$this->load->model('page_model');
		$this->load->model('layout_model');
		$page = $this->page_model->get_page($page_alias);
		
		// We start rendering from the layout
		$layout = $this->layout_model->get_layout($page->layout, 'id');

		$page = $this->render_content($layout->content);
		
		return $page;
	}
	
	function render_content($content)
	{
		// Extract function calls from content
		$get_tag_regex = '/' . preg_quote(OPEN_TAG, '/') . '(.*?)' . preg_quote(CLOSE_TAG, '/') . '/';
		preg_match_all($get_tag_regex, $content, $function_calls);
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
		$result = "";
		try
		{
			$this->load->library($function->module);
			
			if (method_exists($function->module, 'TAG_' . $function->function))
			{
				$result = $this->{$function->module}->{'TAG_'. $function->function}($function->parameters);
			}
			else
			{
				throw new Exception("The module you are calling does not contain the function: {$function->function}", 1);
			}
		}
		catch (exception $e)
		{
			$error = $e->getMessage();
			
			$error = str_replace('class', 'module', $error); // Get the language right to avoid confusion!
			
			$result = "<!-- Error: $error -->";
		}
		
		return $result;
	}
}
