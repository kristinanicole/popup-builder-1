jQuery( document ).ready(function( $ ) {
  	jQuery('[id=sg_colorbox_theme2-css]').remove();
  	jQuery('[id=sg_colorbox_theme3-css]').remove();
  	jQuery('[id=sg_colorbox_theme4-css]').remove();
  	jQuery('[id=sg_colorbox_theme5-css]').remove();
});
jQuery(".sg-show-popup").bind('click',function() {
	var sgPopupID = jQuery(this).attr("sgpopupid");
	showPopup(sgPopupID,false);
});

function optionConvertBool(optionName) {
	returnValue = (optionName) ? true : false;
	return returnValue;
}

function canOpenPopup(id, openOnce, onLoad) {
	if (!onLoad) return true;

	if(typeof(sgPopup) !== 'undefined') {
		
		return sgPopup.canOpenOnce(id);
	}	
	

	return true;
}

function showPopup(id, onLoad) {
	
	var data = SG_POPUP_DATA[id];

	var openOnce = optionConvertBool(data['repeatPopup']);;

	if (!canOpenPopup(data['id'], openOnce, onLoad)) return;

	popupColorboxUrl = SG_APP_POPUP_URL+'/style/sgcolorbox/'+data['theme'];
	jQuery('[id=sg_colorbox_theme-css]').remove();
	head = document.getElementsByTagName('head')[0];
	link = document.createElement('link')
	link.type = "text/css";
	link.id = "sg_colorbox_theme-css";
	link.rel = "stylesheet"
	link.href = popupColorboxUrl;
	head.appendChild(link);
	link.onload = function () {

		setTimeout(function() {
		
			sg_popup_popupFixed = optionConvertBool(data['popupFixed']);
			sg_popup_overlayClose = optionConvertBool(data['overlayClose']);
			sg_popup_contentClick = optionConvertBool(data['contentClick']);
			sg_popup_reposition = optionConvertBool(data['reposition']);
			sg_popup_scrolling = optionConvertBool(data['scrolling']);
			sg_popup_escKey = optionConvertBool(data['escKey']);
			sg_popup_closeButton = optionConvertBool(data['closeButton']);
			sg_popup_forMobile = optionConvertBool(data['forMobile']);
			sg_popup_cantClose = optionConvertBool(data['disablePopup']);
			sg_popup_autoClosePopup = optionConvertBool(data['autoClosePopup']);

			if(sg_popup_cantClose) {
				sgPopup.cantPopupClose();
			}
			sg_popup_popupPosition = data['fixedPostion'];
			sg_popup_html = (data['html'] == '') ? '&nbsp;' : data['html'];
			sg_popup_image = data['image'];
			sg_popup_iframe_url = data['iframe'];
			sg_popup_shortCode = data['shortCode'];
			sg_popup_video = data['video'];
			sg_popup_overlayColor = data['sgOverlayColor'];
			sg_popup_width = data['width'];
			sg_popup_height = data['height'];
		
			sg_popup_html = (sg_popup_html) ? sg_popup_html: false;
			sg_popup_iframe = (sg_popup_iframe_url) ? true: false;
			sg_popup_video = (sg_popup_video) ? sg_popup_video : false;
			sg_popup_image = (sg_popup_image) ? sg_popup_image : false;
			sg_popup_photo = (sg_popup_image) ? true : false;
			sg_popup_shortCode = (sg_popup_shortCode) ? sg_popup_shortCode : false;
			if(sg_popup_shortCode && sg_popup_html == false) {
				sg_popup_html = sg_popup_shortCode;
			}
			if(sg_popup_iframe_url) {
				sg_popup_image = sg_popup_iframe_url;
			}
			if(sg_popup_video) {
				if(sg_popup_width == '') sg_popup_width = '50%';
				if(sg_popup_height == '') sg_popup_height = '50%';
				sg_popup_iframe = true;
				sg_popup_image = sg_popup_video;
			}

			sg_popup_id = data['id'];

			if(sg_popup_popupFixed == true) {
				if(sg_popup_popupPosition == 1) {
					popupPositionTop = "3%";
					popupPositionBottom = false; 
					popupPositionLeft = "0%";
					popupPositionRight = false; 
					sgfixedPositonTop = 0;
					sgfixedPsotonleft = 0;
				}
			
				if(sg_popup_popupPosition == 3) {
					popupPositionTop = "3%";
					popupPositionBottom =  false; 
					popupPositionLeft = false;
					popupPositionRight = "0%"; 
					sgfixedPositonTop = 0;
					sgfixedPsotonleft = 90;
				}
				if(sg_popup_popupPosition == 5) {	
					sg_popup_popupFixed = true;
					popupPositionTop = false;
					popupPositionBottom =  false; 
					popupPositionLeft = false;
					popupPositionRight =  false;
					sgfixedPositonTop = 50;
					sgfixedPsotonleft = 50;
				}
				if(sg_popup_popupPosition == 7) {
					popupPositionTop = false;
					popupPositionBottom = "0%"; 
					popupPositionLeft = "0%";
					popupPositionRight =  false; 
					sgfixedPositonTop = 90;
					sgfixedPsotonleft = 0;
					
				}
				if(sg_popup_popupPosition == 9) {
					popupPositionTop = false;
					popupPositionBottom = "0%"; 
					popupPositionLeft = false;
					popupPositionRight = "0%"; 
					sgfixedPositonTop = 90;
					sgfixedPsotonleft = 90;
				}
			}
			else  {
				popupPositionTop = false;
				popupPositionBottom = false; 
				popupPositionLeft = false;
				popupPositionRight = false;
				sgfixedPositonTop = 50;
				sgfixedPsotonleft = 50;
			}
			if(sg_popup_forMobile) {
				var userDivce = sgPopup.forMobile();
			}
			else {
				userDivce = false;
			}
			if(sg_popup_autoClosePopup) {
				sgPopup.cantPopupClose();
				setTimeout(autoClosePopup, popupClosingTimer*1000);
				function autoClosePopup() {
					sg_prmomotional_overlayClose = true;
					jQuery.sgcolorbox.close();
					
				}
			}
			if(userDivce) return;

			jQuery.sgcolorbox({
				width: sg_popup_width,
				height: sg_popup_height,
				onOpen:function() {
					jQuery('#sgcolorbox').removeAttr('style');
					jQuery('#sgcolorbox').removeAttr('left');
					jQuery('#sgcolorbox').css('top',''+sgfixedPositonTop+'%');
					jQuery('#sgcolorbox').css('left',''+sgfixedPsotonleft+'%');
					jQuery('#sgcolorbox').css('animation-duration', data['duration']+"s");
					jQuery('#sgcolorbox').css('-webkit-animation-duration', data['duration']+"s");
					jQuery("#sgcolorbox").removeAttr("class");
					jQuery("#sgcolorbox").addClass('animated '+data['effect']+'');
					jQuery("#sgcboxOverlay").addClass("sgcboxOverlayBg");
					jQuery("#sgcboxOverlay").removeAttr('style');
					if(sg_popup_overlayColor) {
						jQuery("#sgcboxOverlay").css({'background' : 'none', 'background-color' : sg_popup_overlayColor});
					}

				},
				onLoad: function(){
					
				},
				onComplete: function(){
					
				},
				html: sg_popup_html,
				photo: sg_popup_photo,
				iframe: sg_popup_iframe,
				href: sg_popup_image,
				opacity: data['opacity'],
				escKey: sg_popup_escKey,
				closeButton: sg_popup_closeButton,
				fixed:  sg_popup_popupFixed,
				top: popupPositionTop,
				bottom: popupPositionBottom,
				left: popupPositionLeft,
				right: popupPositionRight,
				scrolling: sg_popup_scrolling,
				reposition: sg_popup_reposition,
				overlayClose: sg_popup_overlayClose,
				maxWidth: data['maxWidth'],
				maxHeight: data['maxHeight'],
				initialWidth: data['initialWidth'],
				initialHeight: data['initialHeight']
			});

			if(data['id'] && onLoad==true && openOnce != '') {
				jQuery.cookie("sgPopupNumbers",data['id'], { expires: 7});
			}
			
			if(sg_popup_contentClick) {
				jQuery('#sgcolorbox').bind('click',function() {
					jQuery.sgcolorbox.close();
				});
			}

		},data['delay']*1000);	
	}
}