<h2>Add New Popup</h2>
<div class="popupsWrapper">
	<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=image">
		<div class="popupsDiv imagePopup">	
			
		</div>
	</a>
	<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=html">
		<div class="popupsDiv htmlPopup">
		</div>
	</a>
	<?php if(SG_POPUP_PRO) { ?> 
		<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=iframe">
			<div class="popupsDiv iframePopup">
			</div>
		</a>
		<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=shortcode">
			<div class="popupsDiv shortcodePopup">
			</div>
		</a>
		<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=video">
			<div class="popupsDiv videoPopup">
			</div>
		</a>
		<a class="createPopupLink" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=ageRestriction">
			<div class="popupsDiv ageRestriction">
			</div>
		</a>
	<?php } ?>
</div>
