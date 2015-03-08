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

# Initialise template engine:
$smarty = get_smarty();

$user_details = ucsd_api_call('userAPIGetMyLoginProfile', '{}');
# Send the request to get all service requests:
$response = ucsd_api_call_admin('userAPIGetServiceRequests', '{}');
# Build up a list of variables for the template engine to use (array index for each status item)
$i = 0;
foreach ($response->{'serviceResult'}->{'rows'} as $entry) {
	# Test if the user has permission to access it - this is SLOW! Must be a better way...
	# For some reason the call userAPIGetServiceRequests doesn't return anything for non-admin?
	if ($user_details->{'serviceResult'}->{'role'} != 'Admin') {
		$response = ucsd_api_call('userAPIGetServiceRequestWorkFlow',
		   '{param0:'.$entry->{'Service_Request_Id'}.'}', false);
		if ($response->{'serviceError'} != null) {
			continue;
		}
	}
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

