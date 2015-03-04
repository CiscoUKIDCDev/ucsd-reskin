<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * submit_api_request.php
 * This file builds the REST request and fires it to UCS Director
 *******************************************************************/

include 'api/ucsd_api.php';
include 'api/smarty.php';

if (!isset($_POST['Catalog_Name'])) {
	# Print error page
	print "Error - no task (fix later)";
	exit;
}
# Construct request:
$request['param0'] = $_POST['Catalog_Name'];
# Loop through inputs:
$i = 0;
foreach (array_keys($_POST) as $post) {
	if (preg_match('/^input_/', $post)) {
		$param[$i]['name'] = $post;
		$param[$i]['value'] = $_POST[$post];
		$i++;
	}
}
# Build up an array which we'll convert to JSON:
$list['list'] = $param;
$request['param1'] = $list;
$request['param2'] = 1000;
# Convert to JSON and send:
$response = ucsd_api_call('userAPISubmitVAppServiceRequest', json_encode($request));

# Redirect to status page with new service number:
$newuri = preg_replace('/submit_api_request/', 'status', $_SERVER['REQUEST_URI']);
header('Location: http://'.$_SERVER['HTTP_HOST'].$newuri.'?id='.$response->{'serviceResult'});


