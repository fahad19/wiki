<?php

/**
 * Error reporting
 */
if (strstr($_SERVER['SERVER_NAME'], 'wiki.croogo.org')) {
	error_reporting(0);
}

/**
 * Debug
 */
function pr($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

?>