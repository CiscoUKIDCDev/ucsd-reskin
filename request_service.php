<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * request_service.php
 * This file provides a user interface for a specific workflow item
 *******************************************************************/

include 'api/ucsd_api.php';
include 'api/smarty.php';

# Send the request:
# Thus far I haven't found a way to get all the needed information
# from a single catelog item, so need to pull all and filter out
$catalog_items = ucsd_api_call('userAPIGetAllCatalogs', '{}');

# Initialise template engine:
$smarty = get_smarty();

# Iterate through each item:
foreach ($catalog_items->{'serviceResult'}->{'rows'} as $row) {
	if ($row->{'Catalog_Name'} == $_GET['catalog']) {
		# Build template items:
		$smarty->assign('Catalog_Name', $row->{'Catalog_Name'});
		$smarty->assign('Image', $row->{'Icon'});
		$smarty->assign('IP_Addr', $GLOBALS['ucsd_ip']);
		$smarty->assign('Description', $row->{'Catalog_Description'});

		# Get input details:
		$detail = ucsd_api_call('userAPIWorkflowInputDetails',
	 	  '{param0:"'.$row->{'Catalog_Name'}.'"}');
		# Add form elements:
		$i = 0;
		# Iterate through inputs:
		foreach ($detail->{'serviceResult'}->{'details'} as $input) {
		 	$form = ucsd_input_supported($input);
		 	if ($form == false) {
				# The input method isn't supported, don't show and skip to next
				continue;
			}
			else {
				# Input is supported, add it to output buffer with html inline for now
				$inputs[$i]['label'] = $form[0];
				$inputs[$i]['form'] = $form[1];
				$i++;

			}
		}
		$smarty->assign('inputs', $inputs);
	}
}


# Output to template engine:
$smarty->display('request_service.tpl');

?>

