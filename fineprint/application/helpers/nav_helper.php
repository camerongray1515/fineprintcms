<?php
function top_nav_item($alias, $inner_html, $controller, $icon=FALSE)
{
	$ci =& get_instance();
	
	$ci->load->model('login_model');
		
	if (!$ci->login_model->is_allowed_to_access_controller($controller))
	{
		return '';
	}
	
	$icon_html = '';
	if ($icon)
	{
		$icon_html = '<i class="icon-' . $icon . ' icon-white"></i> ';
	}
	
	$html = '<li><a href="' . admin_url($alias) . '">' . $icon_html . $inner_html . '</a></li>';
	
	return $html;
}

function top_buttons_link($alias, $text, $type, $icon=FALSE)
{
	$ci =& get_instance();
		
	$icon_html = '';
	if ($icon)
	{
		$icon_html = '<i class="icon-' . $icon . ' icon-white"></i> ';
	}
	
	$html = '<a href="' . admin_url($alias) . '" class="btn btn-' . $type . '">' . $icon_html . $text . '</a>';
	
	return $html;
}

function top_buttons_button($id, $text, $type, $icon=FALSE)
{
	$ci =& get_instance();
		
	$icon_html = '';
	if ($icon)
	{
		$icon_html = '<i class="icon-' . $icon . ' icon-white"></i> ';
	}
	
	$html = '<button id="' . $id . '" class="btn btn-' . $type . '">' . $icon_html . $text . '</button>';
	
	return $html;
}

function tab_item_link($text, $url, $match_sub_method = FALSE, $current_url = FALSE)
{
	$ci =& get_instance();
	
	if (!$current_url)
	{
		$current_url = current_url();
	}

	// $active = ($url == $current_url) ? ' class="active"' : '';

	$active = (($match_sub_method && strpos($current_url, $url) !== FALSE) || (!$match_sub_method && $current_url == $url)) ? ' class="active"' : '';

	return '<li' . $active . '><a href="' . $url . '">' . $text . '</a></li>';
}
