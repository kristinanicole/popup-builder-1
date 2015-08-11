<?php

	$popupType = @$_GET['type'];
	if (!$popupType) {
		$popupType = 'html';
	}
	if(isset($_GET[id])) {
		$id = (int)$_GET['id'];
		$popupName = "SG".ucfirst(strtolower($popupType));
		$popupClassName = $popupName."Popup";
		require_once(SG_APP_POPUP_PATH ."/classes/".$popupClassName.".php");
		$result = $popupClassName::findById($id);
	
		switch ($popupType) {
			case 'iframe':
				$sgPopupDataIframe = $result->getUrl();
				break;
			case 'video':
				$sgPopupDataVideo = $result->getRealUrl();
				break;
			case 'image':
				$sgPopupDataImage = $result->getUrl();
				break;
			case 'html':
				$sgPopupDataHtml = $result->getContent();
				break;
			case 'shortcode':
				$sgPopupDataShortcode = $result->getShortcode();
				break;
		}
		$title = $result->getTitle();
		$jsonData = json_decode($result->getOptions());
		$sgEscKey = $jsonData->escKey;
		$sgScrolling = $jsonData->scrolling;
		$sgCloseButton = $jsonData->closeButton;
		$sgReposition = $jsonData->reposition;
		$sgOverlayClose = $jsonData->overlayClose;
		$sgOverlayColor = $jsonData->sgOverlayColor;
		$sgContentClick = $jsonData->contentClick;
		$sgOpacity = $jsonData->opacity;
		$sgPopupFixed = $jsonData->popupFixed;
		$sgFixedPostion = $jsonData->fixedPostion;
		$sgOnScrolling = $jsonData->onScrolling;
		$beforeScrolingPrsent = $jsonData->beforeScrolingPrsent;
		$duration = $jsonData->duration;
		$delay = $jsonData->delay;
		$effect = $jsonData->effect;
		$sgInitialWidth = $jsonData->initialWidth;
		$sgInitialHeight = $jsonData->initialHeight;
		$sgWidth = $jsonData->width;
		$sgHeight = $jsonData->height;
		$sgMaxWidth = $jsonData->maxWidth;
		$sgMaxHeight = $jsonData->maxHeight;
		$sgForMobile = $jsonData->forMobile;
		$sgCountryStataus = $jsonData->countryStataus;
		$sgCountryIso = $jsonData->countryIso;
		$sgCountryName = $jsonData->countryName;
		$sgRepeatPopup = $jsonData->repeatPopup;
		$sgCountryAllow = $jsonData->allowCountris;
		$sgDisablePopup = $jsonData->disablePopup;
		$sgPopupClosingTimer = $jsonData->popupClosingTimer;
		$sgAutoClosePopup = $jsonData->autoClosePopup;
		$sgTheme = $jsonData->theme;
	}
	
	$colorbox_deafult_values = array('escKey'=> true,'closeButton' => true,'scale'=> true, 'scrolling'=> true,'opacity'=>0.8,'reposition' => true,'width' => false,'height' => false,'initialWidth'=>'300','initialHeight'=>'100','maxWidth'=>false,'maxHeight'=>false,'overlayClose'=>true,'contentClick'=>true,'fixed'=>false,'top'=>false,'right'=>false,'bottom'=>false,'left'=>false,"duration"=>1,"delay"=>0);
	$escKey = ($colorbox_deafult_values['escKey'] == true ? 'checked' : '');
	$closeButton = ($colorbox_deafult_values['closeButton'] == true ? 'checked' : '');
	$scale = ($colorbox_deafult_values['scale'] == true ? 'checked' : '');
	$scrolling = ($colorbox_deafult_values['scale'] == true ? 'checked' : '');
	$width = $colorbox_deafult_values['width'];
	$height = $colorbox_deafult_values['height'];
	$reposition = ($colorbox_deafult_values['reposition'] == true ? 'checked' : '');
	$overlayClose = ($colorbox_deafult_values['overlayClose'] == true ? 'checked' : '');
	$contentClick = ($colorbox_deafult_values['contentClick'] == true ? 'checked' : '');
	$opacityValue = $colorbox_deafult_values['opacity'];
	$top = $colorbox_deafult_values['top'];
	$right = $colorbox_deafult_values['right'];
	$bottom = $colorbox_deafult_values['bottom'];
	$left = $colorbox_deafult_values['left'];
	$initialWidth = $colorbox_deafult_values['initialWidth'];
	$initialHeight = $colorbox_deafult_values['initialHeight'];
	$maxWidth = $colorbox_deafult_values['maxWidth'];
	$maxHeight = $colorbox_deafult_values['maxHeight'];
	$deafultFixed = $colorbox_deafult_values['fixed'];
	$defaultDuration = $colorbox_deafult_values['duration'];
	$defaultDelay = $colorbox_deafult_values['delay'];

	//seted value
	if(isset($sgEscKey)) {$sgEscKey = ($sgEscKey == '') ? '': 'checked'; } else {$sgEscKey = $escKey;}
	if(isset($sgCloseButton)) {$sgCloseButton = ($sgCloseButton == '') ? '': 'checked'; } else {$sgCloseButton = $closeButton;}
	if(isset($sgScrolling)) {$sgScrolling = ($sgScrolling == '') ? '': 'checked'; } else {$sgScrolling = $scrolling;}
	if(isset($sgReposition)) {$sgReposition = ($sgReposition == '') ? '': 'checked'; } else {$sgReposition = $reposition;}
	if(isset($sgOverlayClose)) {$sgOverlayClose = ($sgOverlayClose == '') ? '': 'checked'; } else {$sgOverlayClose = $overlayClose;}
	if(isset($sgContentClick)) {$sgContentClick = ($sgContentClick == '') ? '': 'checked'; } else {$sgContentClick = $contentClick;}
	if(isset($sgPopupFixed)) {$sgPopupFixed = ($sgPopupFixed == '') ? '': 'checked'; } else {$sgPopupFixed = $deafultFixed;}
	if(isset($sgOnScrolling)) {$sgOnScrolling = ($sgOnScrolling == '') ? '': 'checked'; }
	if(isset($sgForMobile)) {$sgForMobile = ($sgForMobile == '') ? '': 'checked'; }
	if(isset($sgRepeatPopup)) {$sgRepeatPopup = ($sgRepeatPopup == '') ? '': 'checked'; }else{$sgRepeatPopup = '';} //$sgCountryStataus
	if(isset($sgCountryStataus)) {$sgCountryStataus = ($sgCountryStataus== '') ? '': 'checked'; }else{$sgCountryStataus = '';}
	if(isset($sgDisablePopup)) {$sgDisablePopup = ($sgDisablePopup== '') ? '': 'checked'; }else{$sgDisablePopup = '';}
	if(isset($sgAutoClosePopup)) {$sgAutoClosePopup = ($sgAutoClosePopup== '') ? '': 'checked'; }else{$sgAutoClosePopup = '';}
	if(!isset($sgOpacity )) {$sgOpacity = $opacityValue;}
	if(!isset($sgWidth )) {$sgWidth = $width;}
	if(!isset($sgHeight )) {$sgHeight = $height;}
	if(!isset($sgInitialWidth )) {$sgInitialWidth = $initialWidth;}
	if(!isset($sgInitialHeight)) {$sgInitialHeight = $initialHeight;}
	if(!isset($sgMaxWidth)) {$sgMaxWidth = $maxWidth;}
	if(!isset($sgMaxWidth)) {$sgMaxHeight = $maxHeight;}
	if(!isset($duration)) {$duration = $defaultDuration;}
	if(!isset($delay)) {$delay = $defaultDelay;}
	if(!isset($sgPopupDataIframe)) {$sgPopupDataIframe = 'http://';}
	if(!isset($sgPopupDataHtml)) {$sgPopupDataHtml = '';}
	if(!isset($sgPopupDataImage)) {$sgPopupDataImage = '';}


	//select basa value or deafult value	
	$sg_popup_effects = array("No effect"=>"No Effect","flip"=>"flip","shake"=>"shake","wobble"=>"wobble","swing"=>"swing","flash"=>"flash","bounce"=>"bounce","pulse"=>"pulse","rubberBand"=>"rubberBand","tada"=>"tada","fadeIn"=>"fadeIn");
	$sg_popup_theme = array("colorbox1.css","colorbox2.css","colorbox3.css","colorbox4.css","colorbox5.css");
	function creaeSelect($options,$name,$selecteOption)
	{
		$selected ='';
		$str = "";
		$checked = "";
		if($name == 'theme') {
			
				$popup_style_name = 'popup_theme_name';
				$firstOption = array_shift($options);
				$i = 1;
				foreach($options as $key){	
					if($key == $selecteOption) {
						$checked = "checked";
					}

					else {
						$checked ='';
					}
					$i++;
					$str .= "<input type='radio' name=\"$name\" value=\"$key\" $checked class='popup_theme_name' sgPoupNumber=".$i.">";		

				}
				if ($checked == ''){
					$checked = "checked";
				}
				$str = "<input type='radio' name=\"$name\" value=\"".$firstOption."\" $checked class='popup_theme_name' sgPoupNumber='1'>".$str;
				return $str;
		}
		else {
			$str .= "<select name=$name id='sameWidthinputs' class=$popup_style_name >";
			foreach($options as $key=>$option) {	
				if($key == $selecteOption) {
					$selected = "selected";
				}
				else {
					$selected ='';
				}		
				$str .= "<option value='".$key."' ".$selected."  >$option</potion>";		
			}

			$str .="</select>" ;
			return $str;

		}

	} ?>

	<?php
		if(isset($_GET['saved']) && $_GET['saved']==1)
		{
			echo '<div id="defaultMessage" class="updated notice notice-success is-dismissible" ><p>Popup updated.</p></div>';
		}
	?>
<form method="POST" action="<?php echo SG_APP_POPUP_ADMIN_URL;?>admin-post.php" id="add-form">
<input type="hidden" name="action" value="save_popup">
	<div class="crudWrapper">
		<div class="cereateTitleWrapper">
			<div class="Sg_title_crud">
				<?php if(isset($id))
					{ ?>
						<h2>Edit popup</h2>

				<?php }
				else  { ?>
					<h2>Create new popup</h2>
			<?php } ?>
			</div>
			<div class="buttonWrapper">
				<p class="submit">
					<?php 
					if(!SG_POPUP_PRO) { ?>
						<input class="crudToPro" type="button" value="Upgrade to PRO version" onclick="window.open('<?php echo SG_POPUP_PRO_URL;?>')"><div class="clear"></div>
					<?php } ?> 
					<input type="submit" id="promotionalSaveButton" class="button-primary" value="<?php _e('Save Changes') ?>" style='<?php echo $cssClass;?>' />
					<img id="createAjaxLoader" src="<?php echo plugins_url('img/wpspin_light.gif', dirname(__FILE__).'../');?>" style="display: none">
				</p>
			</div>
		</div>
		<div class="clear"></div>
		<div class="generalWrapper">
		<div id="titlediv">
			<div id="titlewrap">
				<input style="margin-top: 5px;" type="text" name="title" size="30" value="<?php echo esc_attr($title)?>" id="title" spellcheck="true" autocomplete="off" required = "required"  placeholder='Enter title here'>
			</div>
		</div>
			<div id="leftMainDiv">
				<div id="general">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="postbox-container-2" class="postbox-container">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox popupBuilder_general_postbox sgSameWidthPostBox" style="display: block;">
									<div class="handlediv generalTitle" title="Click to toggle"><br></div>
									<h3 class="hndle ui-sortable-handle generalTitle" style="cursor: pointer"><span>General</span></h3>
									<div class="generalContent sgSameWidthPostBox">
										<?php require_once("main_".$popupType."_section.php");?>
										<input type="hidden" name="type" value="<?php echo $popupType;?>">
										<span class="createDescribe" id="themeSPan">Popup theme:</span>
										<?php echo creaeSelect($sg_popup_theme,'theme',esc_html($sgTheme));?><div class="theme1" id="displayNone"></div><div class="theme2" id="displayNone"></div><div class="theme3" id="displayNone" ></div><div class="theme4" id="displayNone" ></div><div class="theme5" id="displayNone"></div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div id="effect">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="postbox-container-2" class="postbox-container">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox popupBuilder_effect_postbox sgSameWidthPostBox" style="display: block;">
									<div class="handlediv effectTitle" title="Click to toggle"><br></div>
									<h3 class="hndle ui-sortable-handle effectTitle" style="cursor: pointer"><span>Effects</span></h3>
									<div class="effectsContent">
										<span class="createDescribe">Effect type:</span>
										<?php echo creaeSelect($sg_popup_effects,'effect',esc_html($effect));?>
										<div class="effectWrapper"><div id="effectShow" ></div></div>
										
										<span  class="createDescribe">Effect duration:</span>
										<input class='sameWidthinputs' type="text" name="duration" value="<?php echo esc_attr($duration); ?>" pattern = "\d+" title="It's must be number" /><span class="dashicons dashicons-info contentClick infoImageDuration sameImageStyle"></span><span class="infoDuration samefontStyle">Specify how long the popup appearance animation should take (in sec).</span></br>
										
										<span  class="createDescribe">Initial delay:</span>
										<input class='sameWidthinputs' type="text" name="delay" value="<?php echo esc_attr($delay);?>"  pattern = "\d+" title="It's must be number"/><span class="dashicons dashicons-info contentClick infoImageDelay sameImageStyle"></span><span class="infoDelay samefontStyle">Specify how long the popup appearance should be delayed after loading the page (in sec).</span></br>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				
				
			<?php require_once("options_".$popupType."_section.php");?>
			</div>	
			<div id="rightMaindiv">
				<div id="rightMain">
					<div id="dimentions">
						<div id="post-body" class="metabox-holder columns-2">
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<div class="postbox popupBuilder_dimention_postbox sgSameWidthPostBox" style="display: block;">
										<div class="handlediv dimentionsTitle" title="Click to toggle"><br></div>
										<h3 class="hndle ui-sortable-handle dimentionsTitle" style="cursor: pointer"><span>Dimensions</span></h3>
										<div class="dimensionsContent">
											<span  class="createDescribe">Width:</span>
											<input  class='sameWidthinputs' type="text" name="width" value="<?php echo esc_attr($sgWidth); ?>"  pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span  class="createDescribe">Height:</span>
											<input class='sameWidthinputs' type="text" name="height" value="<?php echo esc_attr($sgHeight);?>" pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span  class="createDescribe">Max width:</span>
											<input class='sameWidthinputs' type="text" name="maxWidth" value="<?php echo esc_attr($sgMaxWidth);?>"  pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span  class="createDescribe">Max height:</span>
											<input class='sameWidthinputs' type="text" name="maxHeight" value="<?php echo esc_attr($sgMaxHeight);?>"   pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span  class="createDescribe">Initial width:</span>
											<input class='sameWidthinputs' type="text" name="initialWidth" value="<?php echo esc_attr($sgInitialWidth);?>"  pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span  class="createDescribe">Initial height:</span>
											<input class='sameWidthinputs' type="text" name="initialHeight" value="<?php echo esc_attr($sgInitialHeight);?>"  pattern = "\d+(([px]+|\%)|)" title="It's must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div id="options">
						<div id="post-body" class="metabox-holder columns-2">
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<div class="postbox popupBuilder_options_postbox sgSameWidthPostBox" style="display: block;">
										<div class="handlediv optionsTitle" title="Click to toggle"><br></div>
										<h3 class="hndle ui-sortable-handle optionsTitle" style="cursor: pointer"><span>Options</span></h3>
										<div class="optionsContent">
											<span class="createDescribe">Dismiss on &quot;esc&quot; key:</span>
											<input class='sameWidthinputs' type="checkbox" name="escKey"  <?php echo $sgEscKey;?>/>
											<span class="dashicons dashicons-info escKeyImg sameImageStyle"></span><span class="infoEscKey samefontStyle">The popup will be dismissed when user presses on 'esc' key.</span></br>
											
											<span class="createDescribe" id="createDescribeClose">Show &quot;close&quot; button:</span>
											<input class='sameWidthinputs' type="checkbox" name="closeButton" <?php echo $sgCloseButton;?> />
											<span class="dashicons dashicons-info CloseImg sameImageStyle"></span><span class="infoCloseButton samefontStyle">The popup will contain 'close' button.</span><br>
											
											<span class="createDescribe">Enable content scrolling:</span>
											<input class='sameWidthinputs' type="checkbox" name="scrolling" <?php echo $sgScrolling;?> />
											<span class="dashicons dashicons-info scrollingImg sameImageStyle"></span><span class="infoScrolling samefontStyle">If the containt is larger then the specified dimentions, then the content will be scrollable.</span><br>
											
											<span class="createDescribe">Enable responsiveness:</span>
											<input class='sameWidthinputs' type="checkbox" name="reposition" <?php echo $sgReposition;?> />
											<span class="dashicons dashicons-info repositionImg sameImageStyle"></span><span class="infoReposition samefontStyle">The popup will be resized/repositioned automatically when window is being resized.</span><br>
											
											<span class="createDescribe">Dissmiss on overlay click:</span> 
											<input class='sameWidthinputs' type="checkbox" name="overlayClose" <?php echo $sgOverlayClose;?> />
											<span class="dashicons dashicons-info overlayImg sameImageStyle"></span><span class="infoOverlayClose samefontStyle">The popup will be dismissed when user clicks beyond of the popup area.</span><br>
											
											<span class="createDescribe">Dissmiss on content click:</span> 
											<input class='sameWidthinputs' type="checkbox" name="contentClick" <?php echo $sgContentClick;?> />
											<span class="dashicons dashicons-info contentClick sameImageStyle"></span><span class="infoContentClick samefontStyle">The popup will be dismissed when user clicks inside popup area.</span><br>
											
											<span class="createDescribe">Change overlay color:</span> 
											<div id="colorPiccer"><input  class="sgOverlayColor" id="sgOverlayColor" type="text" name="sgOverlayColor" value="<?php echo esc_attr($sgOverlayColor); ?>" /></div>
											<br>

											<span class="createDescribe" id="createDescribeOpacitcy">Background overlay opacity:</span>
											<div class="slider-wrapper">
												<input type="text" class="js-decimal" value="<?php echo esc_attr($sgOpacity);?>" rel="<?php echo esc_attr($sgOpacity);?>" name="opacity"/>
												<div id="js-display-decimal" class="display-box"></div>
											</div><br>								
											
											<span  class="createDescribe" id="createDescribeFixed">Popup location:</span>
											<input class='sameWidthinputs' type="checkbox" name="popupFixed" id="popupFixed" <?php echo $sgPopupFixed;?> /><br>
											<div class="popopFixeds">
												<span class="forFixWrapperStyle" >&nbsp;</span> 
												<div class="fixedWrapper"  >
													<div class="fixedPositionStyle" id="fixedPosition1" data-sgvalue="1"></div>
													<div  id="fixedPosition2" data-sgvalue="2"></div>
													<div class="fixedPositionStyle" id="fixedPosition3" data-sgvalue="3"></div>
													<div  id="fixedPosition4" data-sgvalue="4"></div>
													<div class="fixedPositionStyle" id="fixedPosition5" data-sgvalue="5"></div>
													<div  id="fixedPosition6" data-sgvalue="6"></div>
													<div class="fixedPositionStyle" id="fixedPosition7" data-sgvalue="7"></div>
													<div  id="fixedPosition8" data-sgvalue="8"></div>
													<div class="fixedPositionStyle" id="fixedPosition9" data-sgvalue="9"></div>
												</div>
											</div>
											<input type="hidden" name="fixedPostion" class="fixedPostion" value="<?php echo esc_attr($sgFixedPostion);?>">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<?php 
						if (SG_POPUP_PRO) {
							require_once("options_pro_section.php");
						}
					?>
				</div>
				</div>
				<div class="clear"></div>
				<input type="hidden" class="button-primary" value="<?php echo esc_attr($id);?>" name="hidden_popup_number" />
			</div>	
		</div>
</form>
