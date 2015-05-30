<?php
function sg_popup_meta()
{
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) 
	{
		add_meta_box( 'prfx_meta', __( 'Select Popup', 'prfx-textdomain' ), 'sg_popup_callback', $screen, 'normal' );
	}	
}
add_action( 'add_meta_boxes', 'sg_popup_meta' );

/**
 * Outputs the content of the meta box
 */
function sg_popup_callback( $post )
{
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	$prfx_stored_meta = get_post_meta( $post->ID );
	?>
	<p class="previewParagaraph">
<?php 
		global $wpdb;
		$proposedTypes = array();
		$proposedTypes = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."sg_promotional_popup ORDER BY id DESC"); 
		function creaeSelect($options,$name,$selecteOption)
		{	
			$selected ='';
			$str = "";
			$str .= "<select class='choosePopupType promotionalPopupSelect'  name=$name>";
			$str .= "<option>Not selected</potion>";
			foreach($options as $option)
			{
				if($option->options)
				{
					$jsonData = json_decode($option->options);
					$title = $jsonData->title;
					$id = $option->id;
					if($selecteOption == $id)
					{
						$selected = "selected";
					}
					else
					{
						$selected ='';
					}
					$str .= "<option value='".$id."' disable='".$id."' ".$selected." >$title</potion>";
				}
			}
			$str .="</select>" ;
			return $str;
		}
		global $post;
		$page = (int)$post->ID;
		$popup = "sg_promotional_popup";
		$sql = $wpdb->prepare("SELECT meta_value  FROM wp_postmeta WHERE post_id = %d AND meta_key =%s",$page,$popup);
		$row = $wpdb->get_row($sql);
		$type = (int)$row->meta_value;
		$prepare = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_promotional_popup WHERE id = %d ",$type);
		$pageSelectionData = $wpdb->get_row($prepare);
		echo creaeSelect($proposedTypes,'sg_promotional_popup',$type);
		 $SG_APP_POPUP_URL = SG_APP_POPUP_URL;
?>
	</p>
	<input type="button" value="Preview"  class="previewbutton" id="previewbuttonStyle" disabled="disabled" /><img src="<?php echo plugins_url('img/wpspin_light.gif', dirname(__FILE__).'../');?>" id="gifLoader" style="display: none;">
	<input type="hidden" value="<?php echo $page;?>" id="post_id">
	<input type="hidden" value="<?php echo $SG_APP_POPUP_URL;?>" id="SG_APP_POPUP_URL">
<?php
}
	add_action('save_post',function($post_id)
	{
		update_post_meta($post_id, 'sg_promotional_popup' , $_POST['sg_promotional_popup']);
	});
	