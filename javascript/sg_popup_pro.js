function sgPopup() {
	
}
sgPopup.canOpenOnce = function(id) {
	if (jQuery.cookie('sgPopupNumbers') != 'undefined' && jQuery.cookie('sgPopupNumbers') == id) {
		return false;
	}
	else {
		return true
	}
}
sgPopup.cantPopupClose = function() {
	sg_popup_escKey = false;
	sg_popup_closeButton = false;
	sg_popup_overlayClose = false;
	sg_popup_contentClick = false;
}
sgPopup.forMobile = function() {
	return jQuery.browser.device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
}
sgPopup.onScrolling = function(popupId) {
	var scrollStatus = false;
		jQuery(window).on('scroll', function(){
			var scrollTop = jQuery(window).scrollTop();
			var docHeight = jQuery(document).height();
			var winHeight = jQuery(window).height();
			var scrollPercent = (scrollTop) / (docHeight - winHeight);
			var scrollPercentRounded = Math.round(scrollPercent*100);
			if(beforeScrolingPrsent < scrollPercentRounded) {
				if(scrollStatus == false) {
					showPopup(popupId,true);
					scrollStatus = true;
				}
			}
		});
}
sgPopup.autoClosePopup = function(popupId,sg_promotional_popupClosingTimer) {
	showPopup(popupId,true);
	
}

/*sgPopup.cantPopupClose();
		setTimeout(autoClosePopup, sg_promotional_popupClosingTimer*1000);
		function autoClosePopup() {
			sg_prmomotional_overlayClose = true;
			colorboxExecute();
		}*/