<?php

class Core {

	function baseUrl() {
		return str_replace('index.php', '', $_SERVER['PHP_SELF']);
	}

	function url($url) {
		if (strstr($url, '://')) {
			return $url;
		}

		$baseUrl = substr(self::baseUrl(), 0, strlen(self::baseUrl()) - 1);
		return $baseUrl . $url;
	}

	function css($filename) {
		$filename = str_replace('.css', '', $filename);
		return '<link rel="stylesheet" type="text/css" href="' . $this->baseUrl() . 'css/' . $filename . '.css" />';
	}

	function js($filename) {
		$filename = str_replace('.js', '', $filename);
		return '<script type="text/javascript" src="' . $this->baseUrl() . 'js/' . $filename . '.js"></script>';
	}

	function getCurrentRoute() {
		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
			return '/' . str_replace('url=', '', $_SERVER['REDIRECT_QUERY_STRING']);
		} else {
			return '';
		}
	}
	
}

?>