<?php
//Route::add('.*\.(jpg|png|svg|webp|ico|mp4|mp3)',function(){
//	$uri = $_SERVER['REQUEST_URI'];
//	$uri = substr($uri, strpos($uri,'/',1)+1);
//	readfile('public/'.$uri);
//	global $jsroot;
//	header("Location: $jsroot/public/$uri");
//});
Route::add('global.css',function (){
	header("Content-Type: text/css");
	require(__DIR__.'/global.css');
});
Route::add('.*\.wasm',function (){
	header("Content-Type: application/wasm");
	$uri = $_SERVER['REQUEST_URI'];
	$uri = substr($uri, strrpos($uri,'/')+1);
	require(__DIR__.'/public/cpp/'.$uri);
});
Route::add('security.js',function (){
	header("Content-Type: text/css");
	require(__DIR__.'/public/cpp/security.js');
});
Route::add('global.js',function (){
	extract($GLOBALS);
	header("Content-Type: text/javascript");
	echo "
        var url = '$url';
        var jsroot = '$jsroot';
    ";
	require(__DIR__.'/global.js');
});
Route::add('[a-zA-Z0-9/_\.\-]+\.css',function (){
	global $jsroot;
	$uri = $_SERVER['REQUEST_URI'];
	$uri = substr($uri, strlen($jsroot)+1);
	if (strpos($uri,'?')!==false)
		$uri = substr($uri,0, strpos($uri,'?'));
	header("Content-Type: text/css");
	require(__DIR__ . '/public/' .$uri);
});
Route::add('[a-zA-Z0-9/_\.\-]+\.js',function (){
	extract($GLOBALS);
	header("Content-Type: text/javascript");
	echo "
var url = '$url';
var jsroot = '$jsroot';
var debug = '$debug';
var cookie_name = '$cookie_name';
    ";
	$uri = $_SERVER['REQUEST_URI'];
	$uri = substr($uri, strlen($jsroot)+1);
	if (strpos($uri,'?')!==false)
		$uri = substr($uri,0, strpos($uri,'?'));
	require(__DIR__.'/public/'.$uri);
},'get',false);
Route::add('(index.(php|html))?',function(){
	extract($GLOBALS);
	if (file_exists(__DIR__.'/public/index.php'))
		require(__DIR__.'/public/index.php');
	else
		require(__DIR__.'/public/index.html');
},'',true);
Route::run();