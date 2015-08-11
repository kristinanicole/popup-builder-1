<?php
function dh_popup_media_button() {
    global $pagenow, $typenow, $wp_version;
    
    $button_title = __('Insert popup');
    $output = '';
    
    // Show button only in post and page edit screens
    if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) {
        /* check current WP version */
        $img = '<span class="dashicons dashicons-welcome-widgets-menus" id="dh-popup-media-button" style="padding: 3px 2px 0px 0px"></span>';
        $output = '<a href="#TB_inline?width=600&height=550&inlineId=sg-popup-thickbox" class="thickbox button" title="' . $button_title . '" style="padding-left: .4em;">' . $img . $button_title . '</a>';
    }
    
    echo $output;
}
add_action( 'media_buttons', 'dh_popup_media_button', 11);

function dh_popup_media_button_thickboxs() {
    global $pagenow, $typenow, $post;

    // Only run in post/page creation and edit screens
    if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) { ?>
    
        <script type="text/javascript"> 
            jQuery(document).ready(function ($) {
                $('#dh-ptp-popup-insert').on('click', function () {
                    var id = $('#sg_popup_id').val();
                   
                    // Return early if no download is selected
                    if ('' === id) {
                        alert('Select your popup');
                        return;
                    }
                    selectionText = (tinyMCE.activeEditor.selection.getContent()) ? tinyMCE.activeEditor.selection.getContent() : 'Popup';
                    window.send_to_editor('[sg_popup id="' + id + '"]'+selectionText+"[/sg_popup]");
                   
                    // Tracking
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                            action: "dh_ptp_tracking_deploy",
                            id: id
                        }
                    });
                });
            });
        </script>
      
        <div id="sg-popup-thickbox" style="display: none;" class="popupShortDiv">
            <div  class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                <p><?php _e('Insert the shortcode for showing a Popup.'); ?></p>
                <div>
                    <select id="sg_popup_id">
                        <option value=""><?php _e('Please select...'); ?></option>
                        <?php
							global $wpdb;
                            $proposedTypes = array();
                            $orderBy = 'id DESC';
                            $allPopups = SGPopup::findAll($orderBy); 
							foreach($allPopups as $allPopup) { ?>
								<option value="<?=$allPopup->getId()?>"><?php echo $allPopup->getTitle();?><?php echo " - ".$allPopup->getType();?></option>;
							<?php } 	?>
                    </select>
                </div>
                <p class="submit">
                    <input type="button" id="dh-ptp-popup-insert" class="button-primary dashicons-welcome-widgets-menus" value="<?php _e('Insert'); ?>"/>
                    <a id="sg_popup_cancel" class="button-secondary" onclick="tb_remove();" title="<?php _e('Cancel'); ?>"><?php _e('Cancel', PTP_LOC); ?></a>
                </p>
            </div>
        </div>
    <?php
    }
}
add_action( 'admin_footer', 'dh_popup_media_button_thickboxs' );