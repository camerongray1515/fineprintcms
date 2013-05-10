<?php
if ( ! function_exists('admin_url'))
{
	function admin_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->site_url(ADMIN_ALIAS . '/' . $uri);
	}
}