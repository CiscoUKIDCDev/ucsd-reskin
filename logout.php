<?php
/******************************************************************** 
 * UCS Director Catalog Re-skin
 * Copyright (c) 2015 Cisco Systems and Matt Day
 * 
 * This file is licensed under the MIT license. See LICENSE for more
 * information.
 *
 * logout.php
 * Logs out a currently logged-in user
 *******************************************************************/

# End the session:
session_start();
session_unset();
session_destroy();

# Redirect to login:
$newuri = preg_replace('/'.basename($_SERVER['PHP_SELF']).'/', 'login.php', $_SERVER['PHP_SELF']);
$redir = $newuri = preg_replace('/'.basename($_SERVER['PHP_SELF']).'/', 'index.php', $_SERVER['PHP_SELF']);
header('Location: http://'.$_SERVER['HTTP_HOST'].$newuri.'?redir='.urlencode($redir).
   '&reason='.urlencode('Logged out successfully'));


?>
