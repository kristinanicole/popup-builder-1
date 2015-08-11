<?php
require_once(dirname(__FILE__).'/SGPopup.php');

class SGImagePopup extends SGPopup {
	private $url;

	public function setUrl($url) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}
	public static function create($data) {
		$obj = new self();
		
		$obj->setUrl($data['image']);

		parent::create($data, $obj);
	}

	public function save() {
		
		$editMode = $this->getId()?true:false;

		$res = parent::save($data);
		if ($res===false) return false;
		
		global $wpdb;
		if ($editMode) {
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_image_popup SET url=%s WHERE id=%d",$this->getUrl(),$this->getId());
			$res = $wpdb->query($sql);
		}
		else {

			$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_image_popup (id, url) VALUES (%d,%s)",$this->getId(),$this->getUrl());	
			$res = $wpdb->query($sql);
		}
		return $res;
	}

	protected function setCustomOptions($id) {
		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_image_popup WHERE id = %d",$id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		$this->setUrl($arr['url']);
	}
	protected function getExtraRenderOptions() {
		return array('image'=>$this->getUrl());
	}

	public  function render() {
		return parent::render();
	}
}
//$data = array('id'=>'49','type'=>'Image','html'=>'','image'=>'fffdd','iframe'=>'','shortCode'=>'','options'=>'{"title":"dddd","width":"300","height":"200","delay":0,"duration":1,"effect":"No effect","escKey":"on","scale":"","scrolling":"on","reposition":"on","overlayClose":"on","opacity":"0.8","popupFixed":"","fixedPostion":"","maxWidth":"","maxHeight":"","initialWidth":"300","initialHeight":"100","closeButton":"on","theme":"colorbox1.css","onScrolling":"","forMobile":"","repeatPopup":"on","countryStataus":"","countryIso":"","countryName":"","allowCountris":"allow"}');
//echo ImagePopup::create($data);
/*$popup = Popup::findById(49);
$popup->setUrl('1234');
$popup->save();*/