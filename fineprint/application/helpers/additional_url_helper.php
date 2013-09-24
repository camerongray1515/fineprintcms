<?php
if ( ! function_exists('admin_url'))
{
	function admin_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->site_url(ADMIN_ALIAS . '/' . $uri);
	}

    function module_frontend_url($uri = '')
    {
        $CI =& get_instance();
        return $CI->config->site_url(FRONTEND_MODULE_ALIAS . '/' . $uri);
    }
	
	function application_base_url($uri = '')
	{
		$CI =& get_instance();
		return base_url("fineprint/$uri");
	}
}