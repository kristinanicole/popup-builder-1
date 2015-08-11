<?php
add_action('admin_post_save_popup', 'savePopupFrom');

	function setOptionvalue($optionsKey) {

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

function savePopupFrom() {

	global $wpdb;
	$array = array();
	
	$array['width'] = setOptionvalue('width');
	$array['height'] = setOptionvalue('height');
	$array['delay'] = (int)setOptionvalue('delay');
	$array['duration'] = (int)setOptionvalue('duration');
	$array['effect'] = setOptionvalue('effect');
	$array['escKey'] = setOptionvalue('escKey');
	$array['scrolling'] = setOptionvalue('scrolling');
	$array['reposition'] = setOptionvalue('reposition');
	$array['overlayClose'] = setOptionvalue('overlayClose');
	$array['sgOverlayColor'] = setOptionvalue('sgOverlayColor');
	$array['contentClick'] = setOptionvalue('contentClick');
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
	$array['beforeScrolingPrsent'] = setOptionvalue('beforeScrolingPrsent');
	$array['forMobile'] = setOptionvalue('forMobile');
	$array['repeatPopup'] = setOptionvalue('repeatPopup');
	$array['countryStataus'] = setOptionvalue('countryStataus');
	$array['countryIso'] = setOptionvalue('countryIso');	
	$array['countryName'] = setOptionvalue('countryName');	
	$array['allowCountris'] = setOptionvalue('allowCountris');
	$array['autoClosePopup'] = setOptionvalue('autoClosePopup');
	$array['popupClosingTimer'] = setOptionvalue('popupClosingTimer');
	$array['disablePopup'] = setOptionvalue('disablePopup');
	$array['autoClosePopup'] = setOptionvalue('autoClosePopup');
	$array['popupClosingTimer'] = setOptionvalue('popupClosingTimer');

	$html = stripslashes(setOptionvalue("sg_popup_html"));
	$image = setOptionvalue('ad_image');
	$iframe = setOptionvalue('iframe');
	$video = setOptionvalue('video');
	$shortCode = stripslashes(setOptionvalue('shortCode'));
	$type = setOptionvalue('type');
	$title = setOptionvalue('title');
	$id = setOptionvalue('hidden_popup_number');
	$jsonDataArray = json_encode($array);
	$data = array('id'=>$id,'title'=>$title,'type'=>$type,'image'=>$image,'html'=>$html,'iframe'=>$iframe,'video'=>$video,'shortcode'=>$shortCode,'options'=>$jsonDataArray);
	$popupName = "SG".ucfirst(strtolower($_POST['type']));
	$popupClassName = $popupName."Popup";

	require_once(SG_APP_POPUP_PATH ."/classes/".$popupClassName.".php");

	if($id == "") {
		global $wpdb;
		$popupClassName::create($data);
		$lastId = $wpdb->get_var("SELECT LAST_INSERT_ID() FROM ".  $wpdb->prefix."sg_popup");
		wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=edit-popup&id=".$lastId."&type=$type&saved=1");
		exit();
	}
	else {

	
		$popup = SGPopup::findById($id);
		$popup->setTitle($title);
		$popup->setId($id);
		$popup->setType($type);
		$popup->setOptions($jsonDataArray);
		switch ($popupName) {
			case 'SGImage':
				$popup->setUrl($image);
				break;
			case 'SGIframe':
				$popup->setUrl($iframe);
				break;
			case 'SGVideo':
				$popup->setUrl($video);
				$popup->setRealUrl($video);
				break;
			case 'SGHtml':
				$popup->setContent($html);
				break;
			case 'SGShortcode':
				$popup->setShortcode($shortCode);
				break;
			
		}
		$popup->save();
		wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=edit-popup&id=$id&type=$type&saved=1");
		exit();
	}	
}
