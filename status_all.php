<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * status_all.php
 * This file shows all ongoing service requests
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


# Initialise template engine:
$smarty = get_smarty();

# Send the request:
$response = ucsd_api_call('userAPIGetTabularReport', '{param0:"6",param1:"",param2:"SERVICE-REQUESTS-T10"}');

#var_dump($response->{'serviceResult'}->{'rows'});

$i = 0;
foreach ($response->{'serviceResult'}->{'rows'} as $entry) {
	$request[$i]['Number'] = $entry->{'Service_Request_Id'};
	$request[$i]['Name'] = $entry->{'Catalog_Workflow_Name'};
	$request[$i]['Status'] = $entry->{'Request_Status'};
	$request[$i]['Started'] = $entry->{'Request_Time'};
	$request[$i]['Owner'] = $entry->{'Initiating_User'};
	$request[$i]['url'] = 'status?id='.$entry->{'Service_Request_Id'};
	$i++;
}

$smarty->assign('requests', $request);



# Output to template engine:
$smarty->display('status_all.tpl');

?>

