<?php
$path = ltrim($_SERVER['REQUEST_URI'], '/');
$elements = explode('/', $path);
if (count($elements) === 0) {
	ShowHomePage();
} else {
	switch(array_shift($elements))
		case '/test/login':
			ShowPicture($elements);
			break;
		default:
			header('HTTP/1.1 404 not found');
			Show404Error();
}
?>