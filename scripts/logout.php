<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Log out script, contains functionality that logs out the user
 */

//Establish session, destroy session, and then take user back to the home page.
session_start();
session_destroy();
header("Location: ../index.php");
exit();