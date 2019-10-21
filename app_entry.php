<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
$tmyname = $_REQUEST["myname"] ?? "";
$tmypwd = $_REQUEST["password"] ?? "";
if (appSessionLoginExists() && ! empty($tmyname) && ! empty($tmypwd))
{
    appSetLoginAndReturn($tmyname);
    appSetLoginAndReturn($tmypwd);
}
else
{
    appGoToError();
}

?>