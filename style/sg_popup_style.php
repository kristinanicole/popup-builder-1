<?php
function sg_popup_admin_style($hook) {
	if ('toplevel_page_PopupBuilder' != $hook && 'popup-builder_page_create-popup' != $hook && 'popup-builder_page_edit-popup' != $hook && 'popup-builder_page_sgPopupMenu' != $hook) {
        return;
    }
	wp_register_style('sg_popup_style', SG_APP_POPUP_URL . '/style/sg_popup_style.css', false, '1.0.0');
	wp_enqueue_style('sg_popup_style');
	wp_register_style('sg_popup_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_popup_animate');

}
add_action('admin_enqueue_scripts', 'sg_popup_admin_style');

function sg_popup_style($hook) {
	if ('admin.php' != $hook && 'post.php' != $hook) {
		return;
	}
	wp_register_style('sg_popup_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_popup_animate');

	wp_register_style('sg_popup_style', SG_APP_POPUP_URL . '/style/sg_popup_style.css', false, '1.0.0');
	wp_enqueue_style('sg_popup_style');
}

add_action('admin_enqueue_scripts', 'sg_popup_style');
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
	if('popup-builder_page_edit-popup' != $hook_suffix)  {
		return;
	}
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('javascript/sg_colorpicker.js',dirname(__FILE__)), array( 'wp-color-picker' ) );
}