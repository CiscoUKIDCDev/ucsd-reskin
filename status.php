<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * status.php
 * This file keeps track of service requests
 *******************************************************************/

include 'api/ucsd_api.php';
include 'api/smarty.php';

# UCS Director uses magic numbers for status, so let's add them here:
$status[0] = "Not started"; $style[0] = "not_started";
$status[1] = "In Progress"; $style[1] = "in_progress";
$status[2] = "Failed"; $style[2] = "failed";
$status[3] = "Completed"; $style[3] = "completed";
$status[4] = "Completed with Warning"; $style[4] = "completed_warn";
$status[5] = "Cancelled"; $style[5] = "cancelled";
$status[6] = "Paused"; $style[6] = "paused";
$status[7] = "Skipped"; $style[7] = "skipped";


# If no service request, redirect to full status page
if (!array_key_exists('id', $_GET)) {
	$newuri = preg_replace('/status/', 'status_all', $_SERVER['REQUEST_URI']);
	header('Location: http://'.$_SERVER['HTTP_HOST'].$newuri);
	exit;
}
else if (!is_int($_GET['id'])) {
	show_error_page('Invalid job ID');
	exit;
}

# Initialise template engine:
$smarty = get_smarty();
$smarty->assign('request', $_GET['id']);

# Send the request:
$response = ucsd_api_call('userAPIGetServiceRequestWorkFlow', '{param0:'.$_GET['id'].'}');


$i = 0;
foreach ($response->{'serviceResult'}->{'entries'} as $entry) {
	$steps[$i]['Name'] = $entry->{'stepId'};
	$steps[$i]['Status'] = $status[$entry->{'executionStatus'}];
	$steps[$i]['Style'] = $style[$entry->{'executionStatus'}];
	$steps[$i]['Number'] = $i + 1;
	$i++;
}

$smarty->assign('steps', $steps);

# Output to template engine:
$smarty->display('status.tpl');

?>

