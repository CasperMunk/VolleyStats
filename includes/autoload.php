<?php
spl_autoload_register(function($className) {
	$file = __DIR__.'/classes/'.$className . '.php';
	if (is_file($file)) {
		require_once($file);
	}else{
		echo 'Could not find class '.$className;
	}
});