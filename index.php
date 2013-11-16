<?php

	// markdown rendering tools
	// goranb (ɔɔ) 2013

	// config
	$extension = 'md'; // .md or .markdown file extension
	$cache_on = true;
	$cache_dir = 'cached'; // cache directory
	$log_file = 'cached/log.log';
	$css_file = 'style.css'; // should be absolute

	// program
	$file = ($_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : 'index').'.'.$extension;
	
	if (is_file($file)){
		header('Content-Type: text/html');
		if ($cache_on){
			$cache_dir = rtrim($cache_dir, '/').'/';
			if (!is_dir($cache_dir)) mkdir($cache_dir) or die('Unable to make the cache directory');
			$md5 = md5_file($file);
			$cache_file = $cache_dir.$md5;
			if (is_file($cache_file)) {
				readfile($cache_file);
				exit;
			}
		}
		// render
		$process = proc_open('markdown --html4tags', array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('file', $log_file, 'a')), $pipes);
		fwrite($pipes[0], file_get_contents($file));
		fclose($pipes[0]);
		$html = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"utf-8\">\n<title>{$_SERVER['QUERY_STRING']}</title>\n<style type=\"text/css\">@import url('{$css_file}')</style>\n</head>\n<body>\n"
			.stream_get_contents($pipes[1])."</body>\n</html>";
		fclose($pipes[1]);
		proc_close($process);
		if ($cache_on) file_put_contents($cache_file, $html);
		echo $html;
		
	} else {
		http_response_code(404);
		echo "Error 404: File does not exist";
	}
?>