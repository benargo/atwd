<?php
require_once('autoload.php');

/**
 * Catch all file
 *
 * This file is called if the existing .htaccess rules don't match anything
 */

$error = new uwe\atwd\error(501, 'User made a badly formed request. Request Method: "'. $_CEMS_SERVER['REQUEST_METHOD'] .'"; Request URL: "'. $_CEMS_SERVER['REQUEST_URI']);
echo $error->response();