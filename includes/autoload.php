<?php
spl_autoload_register(function($className) {
	$file = 'includes/classes/'.$className . '.php';
	if (is_file($file)) {
		require_once($file);
	}else{
		echo 'Could not find class '.$className;
	}
});