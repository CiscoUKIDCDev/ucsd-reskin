<?php
/********************************************************************
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 *
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * ucsd_api.php
 * This file contains various common tasks such as API calls and
 * UI elements to interact with UCS Director
 *******************************************************************/

# Config file:
require_once('config.php');

# Global cache:
$vm_cache = '';

function ucsd_api_call ($opName, $opData) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$GLOBALS['ucsd_ip'].'/app/api/rest?opName='.
		urlencode($opName).'&opData='.urlencode($opData));
	// Set options
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// Set headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, [ "X-Cloupia-Request-Key: ".$GLOBALS['ucsd_api_key'],]);
	// Assume's all is well and lets upstream handle errors:
	return json_decode(curl_exec($ch));
}

# Takes an input object and returns its field, or false if it's not supported
# the returning item should return an array of two items - the label and input field
function ucsd_input_supported($input) {
	switch ($input->{'type'}) {
		case 'gen_text_input':
			return _ucsd_input_plain_text($input);
		case 'vm':
			return _ucsd_input_vm_picker($input);
		case 'vCPUCount':
			return _ucsd_input_vcpu_count($input);
		default:
			return false;
	}
}

# Plain text input form
function _ucsd_input_plain_text ($input) {
	$name = $input->{'name'};
	$form[0] = '<label for="'.$input->{'name'}.'">'.$input->{'label'}.'</label>'."\n";
	$form[1] = '<input type="text" name="'.$input->{'name'}.'" id="'.$input->{'name'}.'" />'."\n";
	return $form;
}

# VM picker:
function _ucsd_input_vm_picker ($input) {
	$name = $input->{'name'};
	$form[0] = '<label for="'.$name.'">'.$input->{'label'}.'</label>'."\n";

	# API call to get full VM list (check cache first):
	if ($GLOBALS['vm_cache'] == '') {
		$GLOBALS['vm_cache'] = ucsd_api_call('userAPIGetTabularReport',
			'{param0:"0",param1:"All Clouds",param2:"VMS-T0"}');
	}
	$form[1] = '<select name="'.$name.'" id="'.$name.'">';
	foreach ($GLOBALS['vm_cache']->{'serviceResult'}->{'rows'} as $row) {
		$form[1] .= '<option value="'.$row->{'VM_ID'}.'">'.$row->{'VM_Name'}.'</option>';
	}
	$form[1] .= '</select>';
	return $form;
}

function _ucsd_input_vcpu_count ($input) {
	$name = $input->{'name'};
	$label = $input->{'label'};
	$form[0] = '<label for="'.$name.'">'.$label.'</label>';
	$form[1] = '<select name="'.$name.'" id="'.$name.'">';
	for ($i = 1; $i <= 64; $i++) {
		$form[1] .= '<option value="'.$i.'">'.$i.'</option>';
	}
	$form[1] .= '</select>';
	return $form;
}


?>