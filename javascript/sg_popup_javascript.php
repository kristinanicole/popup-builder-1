<?php

function sg_set_admin_url($hook) {
	if ('popup-builder_page_create-popup' == $hook) {
		echo '<script type="text/javascript">SG_ADMIN_URL = "'.admin_url()."admin.php?page=create-popup".'";</script>';
	}
}

function sg_popup_admin_scripts($hook) {
    if ('popup-builder_page_create-popup' == $hook) {
		wp_enqueue_media();
		
		wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_backend.js', array('jquery'));
		wp_register_script('sg_popup_rangeslider', SG_APP_POPUP_URL . '/javascript/sg_popup_rangeslider.js', array('jquery'));
		wp_enqueue_script('sg_popup_rangeslider');
		wp_enqueue_script('jquery');
		wp_enqueue_script('javascript');
    }
	else if('toplevel_page_PopupBuilder' == $hook){
		wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_backend.js', array('jquery'));
		wp_enqueue_script('javascript');
		wp_enqueue_script('jquery');
	}
}
add_action('admin_enqueue_scripts', 'sg_set_admin_url');
add_action('admin_enqueue_scripts', 'sg_popup_admin_scripts');

function sg_popup_scripts($hook) {
	if ($hook != 'post.php') {
		return;
	}
	wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_backend.js', array('jquery'));
	wp_register_script('colorbox', SG_APP_POPUP_URL . '/javascript/jquery.colorbox-min.js', array('jquery'));
	wp_register_script('proo', SG_APP_POPUP_URL . '/javascript/sg_popup_pro.js');
	wp_enqueue_script('proo');
	wp_enqueue_script('jquery');
	wp_enqueue_script('colorbox');
	wp_enqueue_script('javascript');
}
add_action('admin_enqueue_scripts', 'sg_popup_scripts');

