<?php
$file = __DIR__ . '/../vendor/autoload.php';

if (file_exists($file)) {
	$loader = include $file;
	if ($loader) {
		return $loader;
	}
}

echo 'You must set up the dependencies using `composer install`'.PHP_EOL.
	'See https://getcomposer.org/download/ for instructions on installing Composer'.PHP_EOL;
exit(1);
