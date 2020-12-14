<?php
spl_autoload_register(function($className) {
	$file = 'includes/classes/'.$className . '.php';
	if (file_exists($file)) {
		include $file;
	}
});