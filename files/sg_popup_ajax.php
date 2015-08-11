<?php
// save ajax


/*
*	delete ajax opretion

*/
function sg_popup_delete() {
		
	$id = (int)$_POST['popup_id'];
	if (!$id) return;
	require_once( SG_APP_POPUP_CLASSES .'/SGPopup.php'); 
	SGPopup::delete($id);
}
add_action('wp_ajax_delete_promotional_popup', 'sg_popup_delete');

function sg_popup_preview() {
	sgFindPopupData($_POST['postId']);
	die();
}
add_action('wp_ajax_get_popup_preview', 'sg_popup_preview');

