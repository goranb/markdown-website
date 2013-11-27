<?php

	// markdown rendering tools
	// goranb (ɔɔ) 2013

	// config
	$cache_on = false;
	$cache_dir = 'cached'; // cache directory
	$log_file = 'cached/log.log';
	$template = 'template.php';

	$extensions = array( 
		'md', 
		'markdown', 
		'txt'
		);

	// program
	$page = $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : 'index';

	foreach($extensions as $extension){
		if (is_file($file = $page.'.'.$extension)){
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
			$content = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			proc_close($process);
			// inject into template and output
			$title = str_replace(array('_', '/'), array(' ', ' &middot; '), $page);
			ob_start();
			include($template);
			$html = ob_get_clean();
			if ($cache_on) file_put_contents($cache_file, $content);
			echo $html;
			exit;
		}
	} 

	// not found
	http_response_code(404);
	echo "Error 404: File does not exist";
?>