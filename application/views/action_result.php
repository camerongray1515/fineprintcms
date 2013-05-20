<?php
if ($ajax)
{
	$result['redirect_to'] = $redirect_to;
	
	header("Content-type: application/json");
	
	echo json_encode($result);
}
else
{
	foreach ($result as $key => $value) {
		$this->session->set_flashdata($key, $value);
	}
	
	redirect($redirect_to);
}