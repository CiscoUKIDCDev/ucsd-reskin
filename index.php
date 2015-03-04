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
include 'api/smarty.php';

# Send the request:
$catalog_items = ucsd_api_call('userAPIGetAllCatalogs', '{}');

# Iterate through each item:
foreach ($catalog_items->{'serviceResult'}->{'rows'} as $row) {
	# Only inspect advanced catalog items
	if ($row->{'Catalog_Type'} == 'Advanced') {
		$detail = ucsd_api_call('userAPIWorkflowInputDetails',
	 	  '{param0:"'.$row->{'Catalog_Name'}.'"}');
		$entry['Catalog_Name'] = $row->{'Catalog_Name'};
		$entry['Image'] = $row->{'Icon'};
		$entry['IP_Addr'] = $GLOBALS['ucsd_ip'];
		$entry['Description'] = $row->{'Catalog_Description'};
		$entry['url'] = 'request_service?catalog='.urlencode($row->{'Catalog_Name'});
		# Add form elements:
		# Iterate through inputs:
		foreach ($detail->{'serviceResult'}->{'details'} as $input) {
		 	$form = ucsd_input_supported($input);
		 	if ($form == false) {
				# The input method isn't supported, don't show and skip to next
				continue(2);
			}
			else {
				# Input is supported, add it to output buffer with html inline for now
				$entry['label'] = $form[0];
				$entry['input'] = $form[1];
			}
		}
		# Add to output array for template engine to draw later (by category):
		$output[$row->{'Folder'}][$row->{'Catalog_Name'}] = $entry;
	}
}
# Output to template engine:
$smarty = get_smarty();

# Sort everything alphabetically:
$keys = array_keys($output);
for ($i = 0; $i < sizeof($output); $i++) {
	ksort($output[$keys[$i]], SORT_STRING);
}
ksort($output, SORT_STRING);

$smarty->assign('items', $output);
$smarty->display('index.tpl');

?>

