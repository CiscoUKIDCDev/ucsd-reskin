<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * index.php
 * This file shows a listing of all available UCS Director Catalog
 * workflows and services.
 *******************************************************************/

include 'api/ucsd_api.php';

# Send the request:
$catalog_items = ucsd_api_call('userAPIGetAllCatalogs', '{}');

# Iterate through each item:
foreach ($catalog_items->{'serviceResult'}->{'rows'} as $row) {
	# Only inspect advanced catalog items
	if ($row->{'Catalog_Type'} == 'Advanced') {
		$detail = ucsd_api_call('userAPIWorkflowInputDetails',
	 	  '{param0:"'.$row->{'Catalog_Name'}.'"}');
		# Create an output buffer:
		$out = '<h1>'.$row->{'Catalog_Name'}.'</h1><p>';
		# Add form elements:
		$out .= '<form action="submit_api_request" method="post">';
		# Iterate through inputs:
		foreach ($detail->{'serviceResult'}->{'details'} as $input) {
		 	$form = ucsd_input_supported($input);
		 	if ($form == false) {
				# The input method isn’t supported, don’t show
				# and skip to next
				continue(2);
			}
			else {
				# Input is supported, add it to output buffer
				# with html inline for now
				$out .= '<br />'.$form[0].$form[1].'<br />';
			}
		}
		$out .= '</form></p>';
		# Print the output buffer (will only happen if all fields are
		# supported:
		print $out;
	}
}
?>

