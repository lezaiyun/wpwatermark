<?php
/**
 * Plugin Name: 老部落轻水印插件
 * Plugin URI: https://www.laobuluo.com/2770.html
 * Description: 全网首个实现WordPress固定九宫格、随机位置、满铺水印的插件之一，方便每一个站长实现不同水印效果，加强图片防盗能力。站长互助QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>
 * Author: 老部落（By:老赵）
 * Version: 1.1.2
 * Author URI: https://www.laobuluo.com
 */
require_once 'WaterMarkFunctions.php';


define('WPWaterMark_BASEFOLDER', plugin_basename(dirname(__FILE__)));
define('WPWaterMark_INDEXFILE', 'wpwatermark/index.php');
define('WPWaterMark_VERSION', 0.1);
register_activation_hook( __FILE__, 'wpwatermark_set_options' );
add_filter( 'wp_handle_upload', 'wp_handle_upload_wpwatermark' );
add_action( 'admin_menu', 'wpwatermark_add_setting_page' );
add_filter( 'plugin_action_links', 'wpwatermark_plugin_action_links', 10, 2 );
add_action( 'admin_enqueue_scripts', 'wpwatermark_admin_enqueue_scripts' );
function wpwatermark_set_options() {
	$options = array(
		'version' => WPWaterMark_VERSION,
		'watermark_type' => "text_watermark",
		'watermark_mark_image' => '',
		'text_content' => 'WPWaterMark',
		'text_font' => "simhei.ttf",
		'text_angle' => '0',
		'text_size' => "18",
		'text_color' => "#790000",
		'watermark_position' => "6",
		'watermark_margin' => '80',
		'watermark_diaphaneity' => '100',
		'watermark_spacing' => '30',
	);
	$wpwatermark_options = get_option('wpwatermark_options');
	if(!$wpwatermark_options){
		add_option('wpwatermark_options', $options, '', 'yes');
	};

}

function wp_handle_upload_wpwatermark( $upload ) {
	$mime_types       = get_allowed_mime_types();
	$image_mime_types = array(
		$mime_types['jpg|jpeg|jpe'],
		$mime_types['png'],
	);

	if ( in_array( $upload['type'], $image_mime_types ) ) {
		$wpwatermark_options = get_option('wpwatermark_options');
		switch ($wpwatermark_options['watermark_type']) {
			case "text_watermark":
				wpWaterMarkCreateWordsWatermark(
					$upload['file'],
					$upload['file'],
					$wpwatermark_options['text_content'],
					$wpwatermark_options['watermark_spacing'],
					$wpwatermark_options['text_size'],
					$wpwatermark_options['text_color'],
					$wpwatermark_options['watermark_position'],
					$wpwatermark_options['text_font'],
					$wpwatermark_options['text_angle'],
					$wpwatermark_options['watermark_margin']
				);
				break;
			case "image_watermark":
				wpWaterMarkCreateImageWatermark(
					$upload['file'],
					$wpwatermark_options['watermark_mark_image'],
					$upload['file'],
					$wpwatermark_options['watermark_position'],
					$wpwatermark_options['watermark_diaphaneity'],
					$wpwatermark_options['watermark_spacing'],
					$wpwatermark_options['watermark_margin']
				);
				break;
		}
	}

	return $upload;
}

function wpwatermark_add_setting_page() {
	if (!function_exists('wpwatermark_setting_page')) {
		require_once 'setting_page.php';
	}
	add_menu_page('轻水印插件设置', '轻水印插件设置', 'manage_options', __FILE__, 'wpwatermark_setting_page');
}

function wpwatermark_plugin_action_links($links, $file) {
	if ($file == plugin_basename(dirname(__FILE__) . '/index.php')) {
		$links[] = '<a href="admin.php?page=' . WPWaterMark_BASEFOLDER . '/index.php">设置</a>';
	}
	return $links;
}

function wpwatermark_admin_enqueue_scripts() {
	wp_register_script( 'jqueryColorPicker', plugins_url( 'js/jquery.colorpicker.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'jqueryColorPicker' );
}
