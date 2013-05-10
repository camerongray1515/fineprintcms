<?php
function alert_html($title, $message, $type)
{
		if ($title)
		{
			$title = '<strong>' . $title . '</strong> ';
		}
		else
		{
			$title = '';
		}
		
		$alert_html = '<div class="alert alert-' . $type . '">' . $title . $message . '</div>';
		
		return $alert_html;
}
