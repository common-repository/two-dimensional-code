<?php
/*
Plugin Name: Two Dimensional Code
Plugin URI: http://blog.windigniter.com/2014/05/wordpress-two-dimensional-code/
Description: generate an two-dimensional code image for a url
Version: 1.3
Author: windigniter(chenxue4076@gmail.com)
Author URI: http://windigniter.com
*/

define('TWO_DIMENSIONAL_VERSION', '1.2');
define('TWO_DIMENSIONAL_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) );
define('TWO_DIMENSIONAL_URL', WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) );
define('QRCODE_PATH', '/png/');

require_once( TWO_DIMENSIONAL_DIR . '/qrcode.php');
require_once( TWO_DIMENSIONAL_DIR . '/widget.php' );
require_once( TWO_DIMENSIONAL_DIR . '/sider.php' );

//only for front web, not include admin path
if(!stristr($_SERVER['REQUEST_URI'], 'wp-')){

	$qrcode_url = 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$qrcode_len = strlen($qrcode_url);

	$qrcode_file = qrcode_get_filename_by_url();
	//echo $qrcode_file;

	if($qrcode_len <= 360){
		//echo TWO_DIMENSIONAL_DIR . QRCODE_PATH . $qrcode_file;
		if(!file_exists(TWO_DIMENSIONAL_DIR . QRCODE_PATH . $qrcode_file)){
			QRcode::png($qrcode_url, TWO_DIMENSIONAL_DIR . QRCODE_PATH . $qrcode_file);
		}
	}
}

$qrcodeSider = new QrcodeSider();
$qrcodeSider->w_init('QrcodeSider');

function qrcode_get_filename_by_url(){
	return str_ireplace(array('/','?','&','='),'_',$_SERVER['REQUEST_URI']).'.png';
}

//end index.php