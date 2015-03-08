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

# Initialise session management:
init_session();

# Global cache:
$vm_cache = '';

# Sends a request for the currently logged in user:
function ucsd_api_call ($opName, $opData) {
	return ucsd_api_call_url('http://'.$GLOBALS['ucsd_ip'].'/app/api/rest?opName='.
		urlencode($opName).'&opData='.urlencode($opData));
}

# Sends a request by direct URL for the currently logged in user:
function ucsd_api_call_url ($url) {
	$response = ucsd_api_call_url_admin ($url, $_SESSION['_ucsd_api_key']);
	# Check for errors
	if ($response->{'serviceError'} != null) {
		show_error_page('API request "'.$url.'" failed: '.$response->{'serviceError'});
		exit;
	}
	return $response;
}

# Sends a request as the admin user (from config.php)
function ucsd_api_call_admin ($opName, $opData) {
	return ucsd_api_call_url_admin('http://'.$GLOBALS['ucsd_ip'].'/app/api/rest?opName='.
		urlencode($opName).'&opData='.urlencode($opData), $GLOBALS['ucsd_api_key']);
}

# Sends a request to UCS director with the given API key
function ucsd_api_call_url_admin ($url, $api_key) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	# Set options
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	# Set headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, [ "X-Cloupia-Request-Key: ".$api_key,]);
	return json_decode(curl_exec($ch));
}

# Takes an input object derived from a userAPIWorkflowInputDetails call and returns
# either false (the input isn't supported) or a reference to a function that can draw
# the input item.
function ucsd_input_supported($input) {
	switch ($input->{'type'}) {
		case 'gen_text_input':
			return '_ucsd_input_plain_text';
		case 'vm':
			return '_ucsd_input_vm_picker';
		case 'vCPUCount':
			return '_ucsd_input_vcpu_count';
		case 'memSizeMB':
			return '_ucsd_input_memory_picker';
		default:
			return false;
	}
}

# Takes an input object and returns its field, or false if it's not supported
# the returning item should return an array of two items - the label and input field
function ucsd_draw_input($input) {
	$ref = ucsd_input_supported($input);
	if ($ref != false) {
		return $ref($input);
	}
	return false;
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
	$form[0] = '<label for="'.$name.'">'.$input->{'label'}.'</label>';

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

# Memory Picker

function show_error_page ($error) {
	$smarty = get_smarty();
	$smarty->assign('Description', $error);
	$smarty->display('error.tpl');
}

function _ucsd_input_memory_picker ($input) {
	$name = $input->{'name'};
	$form[0] = '<label for="'.$name.'">'.$input->{'label'}.'</label>';
	$form[1] = '<select name="'.$name.'" id="'.$name.'">';
	$form[1] .= '<option value="256">256 MiB</option>';
	$form[1] .= '<option value="512">512 MiB</option>';
	# Add the rest...
	for ($i = 1024; $i <= 8192; $i += 256) {
		$form[1] .= '<option value="'.$i.'">'.($i / 1024).' GiB</option>';
	}
	$form[1] .= '<option value="16384">16 GiB</option>';
	$form[1] .= '</select>';
	return $form;
}

# vCPU Picker

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

# Initialises the session and grabs variables etc
function init_session () {
	session_start();
	# Check if there's an API key:
	if (!isset($_SESSION['_ucsd_api_key'])) {
		redirect_to_login('You need to be logged in view this page');
	}
	# Also check if it's valid:
	else {
		# Test API key:
		$response = ucsd_api_call_admin('userAPIGetUserLoginProfile','{param0:"'.$_SESSION['_ucsd_username'].'"}');
		if ($response->{'serviceError'} != null) {
			redirect_to_login('Invalid API key (it may have been changed)');
		}
	}
}

function redirect_to_login ($reason = '') {
	# Redirect to login page unless already there:
	if (!preg_match('/login(\.php)?$/', $_SERVER["SCRIPT_FILENAME"])) {
		$newuri = preg_replace('/'.basename($_SERVER['PHP_SELF']).'/', 'login.php', $_SERVER['PHP_SELF']);
		header('Location: http://'.$_SERVER['HTTP_HOST'].$newuri.'?redir='.urlencode($_SERVER['PHP_SELF']).
		   '&reason='.urlencode($reason));
		exit;
	}
}


?>
