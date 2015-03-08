<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * login.php
 * This file handles logging in to the system and authenticating
 * against UCS Director
 *******************************************************************/

include 'api/ucsd_api.php';
include 'api/smarty.php';

if ((!isset($_POST['username'])) && (!isset($_POST['password']))) {
	$redirect = '';
	$reason = '';
	if (!isset($_GET['redir'])) {
		$redirect = preg_replace('/'.basename($_SERVER['PHP_SELF']).'/', 'index.php', $_SERVER['PHP_SELF']);
	}
	else {
		$redirect = $_GET['redir'];
	}
	if (!isset($_GET['reason'])) {
		$reason = 'You need to log on to use this system';
	}
	else {
		$reason = $_GET['reason'];
	}

	show_login_page($redirect, $reason);
}
else {
	# Verify user:
	$request = ucsd_api_call_admin('userAPIVerifyUser', '{param0:"'.$_POST['username'].
	   '",param1:"'.$_POST['password'].'"}');
	if ($request->{'serviceError'} != null) {
		show_login_page($_POST['redirect'], "Error: Login failed: ".$request->{'serviceError'});
		exit;
	}
	else {
		# Get API key:
		$request = ucsd_api_call_admin('userAPIGetRESTAccessKey', '{param0:"'.$_POST['username'].'"}');
		if ($request->{'serviceError'} != null) {
			show_login_page($_POST['redirect'], "Error: Login failed: ".$request->{'serviceError'});
			exit;
		}
		else {
			$_SESSION['_ucsd_api_key'] = $request->{'serviceResult'};
			$_SESSION['_ucsd_username'] = $_POST['username'];
		}
	}
	# Redirect to previous page
	print header('Location: http://'.$_SERVER['HTTP_HOST'].$_POST['redirect']);

}

function show_login_page ($redirect, $reason) {
	$smarty = get_smarty();
	$smarty->assign('redirect', $redirect);
	$smarty->assign('reason', $reason);
	$smarty->display('login.tpl');
}

?>
