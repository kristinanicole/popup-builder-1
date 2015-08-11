<?php
/**
 * Plugin Name: Popup Builder
 * Plugin URI: http://sygnoos.com
 * Description: Create and manage powerful promotion popups for your WordPress blog or website. It's completely free and all features are available.
 * Version: 2.0
 * Author: Sygnoos
 * Author URI: http://www.sygnoos.com
 * License: GPLv2
 */

//create some difine Pats
define("SG_APP_POPUP_PATH", dirname(__FILE__));
define('SG_APP_POPUP_URL', plugins_url('', __FILE__));
define('SG_APP_POPUP_ADMIN_URL', admin_url());
define('SG_APP_POPUP_FILE', plugin_basename(__FILE__));
define('SG_APP_POPUP_FILES', SG_APP_POPUP_PATH . '/files');
define('SG_APP_POPUP_CLASSES', SG_APP_POPUP_PATH . '/classes');
define('SG_APP_POPUP_JS', SG_APP_POPUP_PATH . '/javascript');
define('SG_APP_POPUP_TABLE_LIMIT', 20 );
define('SG_POPUP_VERSION', 2.0);
define('SG_POPUP_PRO', 0);
define('SG_POPUP_PRO_URL', 'http://sygnoos.com/wordpress-popup/');

require_once( SG_APP_POPUP_CLASSES .'/SGPopup.php'); 
require_once( SG_APP_POPUP_CLASSES .'/PopupInstaller.php'); //cretae tables
if(SG_POPUP_PRO) require_once( SG_APP_POPUP_CLASSES .'/PopupProInstaller.php'); //uninstall tables
require_once( SG_APP_POPUP_PATH .'/style/sg_popup_style.php' ); //include our css file
require_once( SG_APP_POPUP_JS .'/sg_popup_javascript.php' ); //include our js file
require_once( SG_APP_POPUP_FILES .'/sg_popup_page_selection.php' );  // include here in page  button for select popup every page

register_activation_hook(__FILE__, 'sg_popup_activate');
register_uninstall_hook(__FILE__, 'sg_popup_deactivate');


add_action('wpmu_new_blog', 'wporg_wpmu_new_blogPopup', 10, 6 );

function wporg_wpmu_new_blogPopup() {
	PopupInstaller::install();
	if(SG_POPUP_PRO) PopupProInstaller::install();
}
function sg_popup_activate() {
//	update_option('SG_POPUP_VERSION', SG_POPUP_VERSION); 
	PopupInstaller::install();
	if(SG_POPUP_PRO) PopupProInstaller::install();
}

function sg_popup_deactivate() {
	delete_option('SG_POPUP_VERSION');
	PopupInstaller::uninstall();
	if(SG_POPUP_PRO) PopupProInstaller::uninstall();
}


add_action("admin_menu","sgAddMenu");
function sgAddMenu() {
	add_menu_page("Popup Builder", "Popup Builder", "manage_options","PopupBuilder","sgPopupMenu","dashicons-welcome-widgets-menus");
	add_submenu_page("PopupBuilder", "All Popups", "All Popups", 'manage_options', "PopupBuilder", "sgPopupMenu");
	add_submenu_page("PopupBuilder", "Add New", "Add New", 'manage_options', "create-popup", "sgCreatePopup");
	add_submenu_page("PopupBuilder", "Edit Popup", "Edit Popup", 'manage_options', "edit-popup", "sgEditPopup");	
}

function sgPopupMenu() {
	require_once( SG_APP_POPUP_FILES . '/sg_popup_main.php');
}

function sgCreatePopup() {
	require_once( SG_APP_POPUP_FILES . '/sg_popup_create.php'); // here is inculde file in the first sub menu	
}

function sgEditPopup() {
	require_once( SG_APP_POPUP_FILES . '/sg_popup_create_new.php');
}

function sgRegisterScripts() {
	SGPopup::$registeredScripts = true;
	wp_register_style('sg_animate', SG_APP_POPUP_URL . '/style/animate.css');	
	wp_enqueue_style('sg_animate');
	wp_register_script('sg_popup_frontend', SG_APP_POPUP_URL . '/javascript/sg_popup_frontend.js', array('jquery'));
	wp_enqueue_script('sg_popup_frontend');
	wp_enqueue_script('jquery');
	wp_register_script('sg_cookie', SG_APP_POPUP_URL . '/javascript/jquery.cookie.js', array('jquery'));
	wp_enqueue_script('sg_cookie');
	wp_register_script('sg_colorbox', SG_APP_POPUP_URL . '/javascript/jquery.colorbox-min.js', array('jquery'), '5.0');	
	wp_enqueue_script('sg_colorbox');
	if(SG_POPUP_PRO) {
		echo "<script type='text/javascript' src = ".SG_APP_POPUP_URL."/javascript/sg_popup_pro.js?ver=4.2.3'></script>";
	}
	echo "<script type='text/javascript'>SG_POPUP_DATA = [];SG_APP_POPUP_URL = '".SG_APP_POPUP_URL."';</script>";
}

function sgRenderPopupScript($id) {
	if (SGPopup::$registeredScripts==false) sgRegisterScripts();
	wp_register_style('sg_colorbox_theme', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox1.css");
	wp_register_style('sg_colorbox_theme2', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox2.css");
	wp_register_style('sg_colorbox_theme3', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox3.css");
	wp_register_style('sg_colorbox_theme4', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox4.css");
	wp_register_style('sg_colorbox_theme5', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox5.css", array(), '5.0');
	wp_enqueue_style('sg_colorbox_theme');
	wp_enqueue_style('sg_colorbox_theme2');
	wp_enqueue_style('sg_colorbox_theme3');
	wp_enqueue_style('sg_colorbox_theme4');
	wp_enqueue_style('sg_colorbox_theme5');
	sgFindPopupData($id);
}

function sgFindPopupData($id) {
	$obj = SGPopup::findById($id);
	if(!empty($obj)) $content = $obj->render();
	echo "<script type='text/javascript'>";
	echo $content;
	echo "</script>";
}

function sgShowShortCode($args, $content) {
	$obj = SGPopup::findById($args['id']);
	wp_register_style('sg_colorbox_theme', SG_APP_POPUP_URL . "/style/sgcolorbox/".$options['theme']."");
	wp_enqueue_style('sg_colorbox_theme');
	if(!$obj) return  $content;
		sgRenderPopupScript($args['id']);
		return "<a href='javascript:void()' class='sg-show-popup' sgpopupid=".$args['id'].">".$content."</a>";
}
add_shortCode('sg_popup', 'sgShowShortCode');

function sgOnloadPopup() {
	$page = get_queried_object_id ();
	$popup = "sg_promotional_popup";
	$popupId = SGPopup::getPagePopupId($page,$popup);
	if(!$popupId) return;
	sgRenderPopupScript($popupId);
	echo "<script>window.onload = function() {
			sgOnScrolling = (SG_POPUP_DATA [$popupId]['onScrolling']) ? SG_POPUP_DATA [$popupId]['onScrolling']: ''; ;
			beforeScrolingPrsent = (SG_POPUP_DATA [$popupId]['onScrolling']) ?  SG_POPUP_DATA [$popupId]['beforeScrolingPrsent']: '';
			autoClosePopup = (SG_POPUP_DATA [$popupId]['autoClosePopup']) ?  SG_POPUP_DATA [$popupId]['autoClosePopup']: '';
			popupClosingTimer = (SG_POPUP_DATA [$popupId]['popupClosingTimer']) ?  SG_POPUP_DATA [$popupId]['popupClosingTimer']: '';
			if(sgOnScrolling) {
				sgPopup.onScrolling($popupId);
			} 
			else {
				showPopup($popupId,true);
			}
		}
			</script>";

}
add_action('wp_head','sgOnloadPopup');
require_once( SG_APP_POPUP_FILES . '/sg_popup_media_buuton.php');
require_once( SG_APP_POPUP_FILES . '/sg_popup_savePopupFrom.php'); // saving form data
require_once( SG_APP_POPUP_FILES . '/sg_popup_ajax.php');

function sg_popup_plugin_loaded() {
    $versionPopup = get_option('SG_POPUP_VERSION');
    if(!$versionPopup || $versionPopup < SG_POPUP_VERSION ) {
    	update_option('SG_POPUP_VERSION', SG_POPUP_VERSION); 
    	PopupInstaller::convert();
    }
}
add_action( 'plugins_loaded', 'sg_popup_plugin_loaded' );