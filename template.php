<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$title;?></title>
	<link rel="stylesheet" href="/markdown/style.css" type="text/css">
	<base href="<?=$base_url;?>">
</head>
<body>
<div id="menu">
<?php

	$menu = array(
		'Naslovnica' => 'index.html',
		'CV' => 'CV.html',
		'submenu' => array(
			'page1' => 'test/test.html',
			'page2' => 'test/index.html',
			),
		);

	function menu($array){
		$out = array();
		foreach($array as $key => $content){
			if (is_array($content)){
				$out[] = '<li><a>'.$key.'</a>'.menu($content).'</li>';
			} else {
				$out[] = '<li><a href="'.$content.'">'.$key.'</a></li>';
			}
		}
		return $out ? '<ul>'.join($out).'</ul>' : '';
	}

	echo menu($menu);

	/*
	function scan($dir = '.'){
		$skip = array(
			'README.md',
			);
		if (count($scan = scandir($dir))) ;//return;
		$out = array();
		foreach($scan as $file){
			if (in_array($extension = pathinfo($file, PATHINFO_EXTENSION), $GLOBALS['extensions']) && is_file($dir.'/'.$file) && !in_array($file, $skip)){
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$out[] = '<li><a href="'.$dir.'/'.$filename.'.html">'.$filename.'</a></li>';
			} else if (is_dir($path = $dir.'/'.$file)){
				if (substr($file, 0, 1) == '.') continue;
				if ($subscan = scan($path)) {
					$out[] = '<li><a class="dir">'.$file.'</a>'.$subscan.'</li>';
				}
			}
		}
		return $out ? '<ul>'.join($out).'</ul>' : '';
	}

	echo scan();
	*/
	
?>	
</div>
<div id="content"><?=$content;?></div>
<div id="footer">powered by <strong>markdown website</strong></div>
</body>
</html>