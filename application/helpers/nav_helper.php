<?php
function top_nav_item($alias, $inner_html, $allowed_roles, $icon=FALSE)
{
	$ci =& get_instance();
	
	$logged_in_role = $ci->login_model->get_logged_in_user('role');
	
	if (array_search($logged_in_role, $allowed_roles) === FALSE)
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
