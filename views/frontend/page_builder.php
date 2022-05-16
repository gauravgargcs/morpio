<?php $html =  file_get_contents('home-page/index.html');
	$html = str_replace('images/', 'home-page/images/', $html) ;
	$html = str_replace('css/', 'home-page/css/', $html) ;
	$html = str_replace('js/', 'home-page/js/', $html) ;
	$html = str_replace('fonts/', 'home-page/fonts/', $html) ;
	$html = str_replace('bat/', 'home-page/bat/', $html) ;
	$html = str_replace('audio/', 'home-page/audio/', $html) ;
	$html = str_replace('video/', 'home-page/video/', $html) ;
	// $html = str_replace('js/', '/project/template/js/', $html) ;
	echo $html;
?>