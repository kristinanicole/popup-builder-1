<?php
/**
 * Plugin Name: Popup Builder
 * Plugin URI: http://sygnoos.com
 * Description: Create and manage powerful promotion popups for your WordPress blog or website. It's completely free and all features are available.
 * Version: 1.0
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
define('SG_APP_POPUP_JS', SG_APP_POPUP_PATH . '/javascript');
define('SG_APP_POPUP_TABLE_LIMIT', 10 );


require_once( SG_APP_POPUP_PATH . '/style/sg_popup_style.php' ); //include our css file
require_once( SG_APP_POPUP_JS  .'/sg_popup_javascript.php' ); //include our js file
require_once( SG_APP_POPUP_FILES . '/sg_popup_page_selection.php' );  // include here in page  button for select popup every page


register_activation_hook(__FILE__, 'sg_popup_activate');
register_deactivation_hook(__FILE__, 'sg_popup_POD_deactivate');
function sg_popup_activate(){
	global $wpdb;
	$sg_popup_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix ."sg_promotional_popup (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `content` varchar(255) NOT NULL,
	  `html` text NOT NULL,
	  `image` varchar(255) NOT NULL,
	  `options` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
	$wpdb->query($sg_popup_base);
}

function sg_popup_POD_deactivate(){
	global $wpdb;	//required global declaration of WP variable
	$delete = "DELETE  FROM wp_postmeta WHERE meta_key = 'sg_promotional_popup' ";
	$wpdb->query($delete);
	$table_name = $wpdb->prefix."sg_promotional_popup";
	$sql = "DROP TABLE ". $table_name;
	$wpdb->query($sql);

}


//create action huk for create menu and subMenu in the admin menu


add_action("admin_menu","addMenu");
function addMenu()
{
	add_menu_page("Theme page title", "Popup Builder", "","Popup Builder","Menu","dashicons-welcome-widgets-menus");
	add_submenu_page("Popup Builder", "Popup Builder", "Popups", 'manage_options', "popups", "Menu");
	add_submenu_page("Popup Builder", "Edit popup", "Create new", 'manage_options', "create-popup", "createPopup");	// it's first sub menu  //example-option-1
}

function Menu()
{
  require_once( SG_APP_POPUP_FILES . '/sg_popup_main.php');
}

function createPopup(){
	require_once( SG_APP_POPUP_FILES . '/sg_popup_create.php'); // here is inculde file in the first sub menu	
}

// Register Script
function sg_popup_dataAdmin() {
	global $wpdb;
	global $post;
	$page = (int)$post->ID;
	$popup = "sg_promotional_popup";
	$sql = $wpdb->prepare("SELECT meta_value  FROM wp_postmeta WHERE post_id = %d AND meta_key =%s",$page,$popup);
	$row = $wpdb->get_row($sql);
	$type = (int)$row->meta_value;
	$result = $wpdb->get_row('SELECT * FROM '. $wpdb->prefix .'sg_promotional_popup WHERE id='.$type.'');

	$jsonData = json_decode($result->options);
	$type = esc_html($result->content);
	$result->html = str_replace("'", "\\'", $result->html);
	$html = wp_kses_post($result->html);
	$html = wp_kses_post($result->html);
	$image = esc_html($result->image);
	$title = esc_html($jsonData->title);
	$effect = esc_html($jsonData->effect);
	$theme = esc_html($jsonData->theme);
	$duration = esc_html($jsonData->duration);
	$delay = esc_html($jsonData->delay);
	$width = esc_html($jsonData->width);
	$height = esc_html($jsonData->height);
	$escKey = esc_html($jsonData->escKey);
	$closeButton = esc_html($jsonData->closeButton);
	$popupFixed = esc_html($jsonData->popupFixed);
	$fixedPostion = esc_html($jsonData->fixedPostion);
	$scale = esc_html($jsonData->scale);
	$scrolling = esc_html($jsonData->scrolling);
	$reposition = esc_html($jsonData->reposition);
	$overlayClose = esc_html($jsonData->overlayClose);
	$opacity = esc_html($jsonData->opacity);
	$maxWidth = esc_html($jsonData->maxWidth);
	$maxHeight = esc_html($jsonData->maxHeight);
	$initialWidth = esc_html($jsonData->initialWidth);
	$initialHeight = esc_html($jsonData->initialHeight);

	echo "<script type=\"text/javascript\">
			var SG_POPUP_VARS = {
				title:'$title',
				html:'$html',
				image:'$image',
				type:'$type',
				effect:'$effect',
				width:'$width',
				height:'$height',
				delay:'$delay',
				duration:'$duration',
				escKey:'$escKey',
				closeButton: '$closeButton',
				popupPostion: '$fixedPostion',
				popupFixed:'$popupFixed',
				scale:'$scale',
				reposition:'$reposition',
				overlayClose:'$overlayClose',
				opacity:'$opacity',
				maxWidth:'$maxWidth',
				maxHeight: '$maxHeight',
				initialWidth:'$initialWidth',
				initialHeight:'$initialHeight',
				siteUrl: '".plugins_url('', __FILE__)."',
			};
		</script>";
	echo '<style type="text/css">
			*{
			 -webkit-animation-duration:'.$duration.'s !important;
				animation-duration:'.$duration.'s !important;
			}
		</style>';

}

// Hook into the 'wp_enqueue_scripts' action
function sg_popup_enqueueScript()
{
	global $wpdb;
	global $post;
	$page = (int)$post->ID;
	$popup = "sg_promotional_popup";
	$sql = $wpdb->prepare("SELECT meta_value  FROM wp_postmeta WHERE post_id = %d AND meta_key =%s","$page",$popup);
	$row = $wpdb->get_row($sql);
	$type = (int)$row->meta_value;
	$result = $wpdb->get_row('SELECT * FROM '. $wpdb->prefix .'sg_promotional_popup WHERE id='.$type.'');
	$jsonData = json_decode($result->options);
		$theme = $jsonData->theme;
	$row = $wpdb->get_row($sql);
	if($row){
		///
		wp_register_script('js', SG_APP_POPUP_URL . '/javascript/jquery.colorbox-min.js', array('jquery'));
		wp_enqueue_script('jquery');
		wp_register_style('styl', SG_APP_POPUP_URL . "/style/sgcolorbox/$theme");
		wp_enqueue_style('styl');
		wp_enqueue_script('js');
		///
		add_action( 'wp_enqueue_scripts', 'sg_popup_dataAdmin' );
		wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_frontend.js', array('jquery'));
		wp_enqueue_script('jquery');
		wp_enqueue_script('javascript');    
		wp_register_style('cssStyl', SG_APP_POPUP_URL . "/style/animate.css");
		wp_enqueue_style('cssStyl');
	}
}

add_action('wp','sg_popup_enqueueScript');
add_action('wp_ajax_get_popup_preview', 'sg_popup_getresults');
function sg_popup_getresults(){
	global $wpdb;
	$page = (int)$_POST['postId'];
	$result = $wpdb->get_row('SELECT * FROM '. $wpdb->prefix .'sg_promotional_popup WHERE id='.$page, ARRAY_A ); //query for get all information about popup
	foreach($result as $key=>$results)
	{
		if($key == 'html')
		{
			$result[$key] = wp_kses_post($results);
		}
		else
		{
			$result[$key] = sanitize_text_field($results);
		}
	}
	$result['sg_promotional_site_url'] = plugins_url('', __FILE__);
	echo json_encode($result);
	exit();
}
add_action('wp_ajax_delete_promotional_popup', 'sg_popup_delete');

function sg_popup_delete() {
	$id = (int)$_POST['popup_id'];
	if (!$id) return;

	global $wpdb;

	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM ". $wpdb->prefix ."sg_promotional_popup WHERE id = %d"
			,$id
		)
	);

	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM wp_postmeta WHERE meta_value = %d and meta_key = 'sg_promotional_popup'"
			,$id
		)
	);
}

function setOptionvalue($optionsKey) 
{	
	if(isset($_POST[$optionsKey]))
	{ 
		if($optionsKey == "sg_popup_html") 
		{
			return wp_kses_post($_POST[$optionsKey]);
		}
			
		return sanitize_text_field($_POST[$optionsKey]);	
	}	
	else 
	{ 
		return "";	
	}
}

add_action('wp_ajax_save_popup', 'sg_save_popup');

function sg_save_popup() {
	global $wpdb;
	$array = array();
	
	$array['title'] = setOptionvalue('title');
	$array['width'] = setOptionvalue('width');
	$array['height'] = setOptionvalue('height');
	$array['delay'] = setOptionvalue('delay');
	$array['duration'] = setOptionvalue('duration');
	$array['effect'] = setOptionvalue('effect');
	$array['escKey'] = setOptionvalue('escKey');
	$array['scale'] = setOptionvalue('scale');
	$array['scrolling'] = setOptionvalue('scrolling');
	$array['reposition'] = setOptionvalue('reposition');
	$array['overlayClose'] = setOptionvalue('overlayClose');
	$array['opacity'] = setOptionvalue('opacity');
	$array['popupFixed'] = setOptionvalue('popupFixed');
	$array['fixedPostion'] = setOptionvalue('fixedPostion');
	$array['maxWidth'] = setOptionvalue('maxWidth');
	$array['maxHeight'] = setOptionvalue('maxHeight');
	$array['initialWidth'] = setOptionvalue('initialWidth');
	$array['initialHeight'] = setOptionvalue('initialHeight');
	$array['closeButton'] = setOptionvalue('closeButton');
	$array['theme'] = setOptionvalue('theme');
	$html = stripslashes(setOptionvalue("sg_popup_html"));
	$image = setOptionvalue('ad_image');$type = setOptionvalue('content');
	$id = setOptionvalue('hidden_popup_number');
	$jsonDataArray = json_encode($array);

	$saved = false;

	if($_POST['title'] == "")
	{
		$errorTitle = "Title cannot be empty";	
	}
	else 
	{
		if($_POST['hidden_popup_number'] == '')
		{	
		
			$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_promotional_popup(content,html,image,options) VALUES (%s,%s,%s,%s)",$type,$html,$image,$jsonDataArray);	
			$wpdb->query($sql);
			$id = $wpdb->insert_id;
			echo $id;
			die();
		}
		else
		{
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_promotional_popup SET content=%s,html=%s,image=%s,options=%s WHERE id=%d",$type,$html,$image,$jsonDataArray,$id);	
			$wpdb->query($sql);
			echo $id;
			die();
		}
	}

	echo '0';
}