<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
	exit();
}
delete_option('wpwatermark_options');
