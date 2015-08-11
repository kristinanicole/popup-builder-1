<?php
function sg_popup_meta()
{
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) 
	{
		add_meta_box( 'prfx_meta', __( 'Select popup on page load', 'prfx-textdomain' ), 'sg_popup_callback', $screen, 'normal' );
	}	
}
add_action( 'add_meta_boxes', 'sg_popup_meta' );

/**
 * Outputs the content of the meta box
 */
function sg_popup_callback($post) {
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	$prfx_stored_meta = get_post_meta( $post->ID );
	?>
	<p class="previewParagaraph">
<?php 
		global $wpdb;
		$proposedTypes = array();
		$orderBy = 'id DESC';
		$proposedTypes = SGPopup::findAll($orderBy); 
		function createSelect($options,$name,$selecteOption) {	
			$selected ='';
			$str = "";
			$str .= "<select class='choosePopupType promotionalPopupSelect'  name=$name>";
			$str .= "<option value=''>Not selected</potion>";
			foreach($options as $option)
			{
				if($option)
				{
					$title = $option->getTitle();
					$type = $option->getType();
					$id = $option->getId();
					if($selecteOption == $id)
					{
						$selected = "selected";
					}
					else
					{
						$selected ='';
					}
					$str .= "<option value='".$id."' disable='".$id."' ".$selected." >$title - $type</potion>";
				}
			}
			$str .="</select>" ;
			return $str;
		}
		global $post;
		$page = (int)$post->ID;
		$popup = "sg_promotional_popup";
		$popupId = SGPopup::getPagePopupId($page,$popup);
		echo createSelect($proposedTypes,'sg_promotional_popup',$popupId);
		$SG_APP_POPUP_URL = SG_APP_POPUP_URL;
?>
	</p>
	
	<input type="hidden" value="<?php echo $SG_APP_POPUP_URL;?>" id="SG_APP_POPUP_URL">
<?php
}

function selectPopupSaved($post_id) {
	if($_POST['sg_promotional_popup'] == '') return;
		update_post_meta($post_id, 'sg_promotional_popup' , $_POST['sg_promotional_popup']);	 
	
}	

add_action('save_post','selectPopupSaved');