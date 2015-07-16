<?php
// save ajax
add_action('wp_ajax_save_popup', 'sg_save_popup');

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

function sg_save_popup() {
	global $wpdb;
	$array = array();
	
	$array['title'] = setOptionvalue('title');
	$array['width'] = setOptionvalue('width');
	$array['height'] = setOptionvalue('height');
	$array['delay'] = (int)setOptionvalue('delay');
	$array['duration'] = (int)setOptionvalue('duration');
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
	$array['onScrolling'] = setOptionvalue('onScrolling');
	$array['repeatPopup'] = setOptionvalue('repeatPopup');
	$html = stripslashes(setOptionvalue("sg_popup_html"));
	$image = setOptionvalue('ad_image');
	$iframe = setOptionvalue('iframeLink');
	$shortCode = stripslashes(setOptionvalue('shortCode'));
	$type = setOptionvalue('content');
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
		
			$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_promotional_popup(content,html,image,iframe,shortCode,options) VALUES (%s,%s,%s,%s,%s,%s)",$type,$html,$image,$iframe,$shortCode,$jsonDataArray);	
			$wpdb->query($sql);
			$id = $wpdb->insert_id;
			echo $id;
			die();
		}
		else
		{
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_promotional_popup SET content=%s,html=%s,image=%s,iframe=%s,shortCode=%s,options=%s WHERE id=%d",$type,$html,$image,$iframe,$shortCode,$jsonDataArray,$id);	
			$wpdb->query($sql);
			echo $id;
			die();
		}
	}
	echo '0';
}

/*
*	delete ajax opretion
*/

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
add_action('wp_ajax_delete_promotional_popup', 'sg_popup_delete');

/*
* preview ajax 
*/

