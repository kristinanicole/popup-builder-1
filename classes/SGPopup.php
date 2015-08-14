<?php

abstract class SGPopup {
	protected $id;
	protected $type;
	protected $title;
	protected $width;
	protected $height;
	protected $delay;
	protected $effectDuration;
	protected $effect;
	protected $initialWidth;
	protected $initialHeight;
	protected $options;
	public static $registeredScripts = false;
	
	public function setType($type){
		$this->type = $type;
	}
	public function getType() {
		return $this->type;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function setWidth($width){
		$this->width = $width;
	}
	public function getWidth() {
		return $this->width;
	}
	public function setHeight($height){
		$this->height = $height;
	}
	public function getHeight() {
		return $this->height;
	}
	public function setDelay($delay){
		$this->delay = $delay;
	}
	public function getDelay() {
		return $this->delay;
	}
	public function setEffectDuration($effectDuration){
		$this->effectDuration = $effectDuration;
	}
	public function getEffectDuration() {
		return $this->effectDuration;
	}
	public function setEffect($effect){
		$this->effect = $effect;
	}
	public function getEffect() {
		return $this->effect;
	}
	public function setInitialWidth($initialWidth){
		$this->initialWidth = $initialWidth;
	}
	public function getInitialWidth() {
		return $this->initialWidth;
	}
	public function setInitialHeight($initialHeight){
		$this->initialHeight = $initialHeight;
	}
	public function getInitialHeight() {
		return $this->initialHeight;
	}
	public function setOptions($options) {
		$this->options = $options;
	}	
	public function getOptions() {
		return $this->options;
	}
	public static function findById($id) {
		
		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup WHERE id = %d",$id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		if(!$arr) return false;
		return self::popupObjectFromArray($arr);

	}

	abstract protected function setCustomOptions($id);

	abstract protected function getExtraRenderOptions();

	private static function popupObjectFromArray($arr, $obj = null) {
		
		$jsonData = json_decode($arr['options'], true);

		$type = notNull($arr['type']);
		
		if ($obj===null) {
			$className = "SG".ucfirst(strtolower($type)).'Popup';
			require_once(dirname(__FILE__).'/'.$className.'.php');
			$obj = new $className();
		}

		$obj->setType(notNull($type));
		$obj->setTitle(notNull($arr['title']));
		if (@$arr['id']) $obj->setId($arr['id']);
		$obj->setWidth(notNull(@$jsonData['width']));
		$obj->setHeight(notNull(@$jsonData['height']));
		$obj->setDelay(notNull(@$jsonData['delay']));
		$obj->setEffectDuration(notNull(@$jsonData['duration']));
		$obj->setEffect(notNull($jsonData['effect']));
		$obj->setInitialWidth(notNull(@$jsonData['initialWidth']));
		$obj->setInitialHeight(notNull(@$jsonData['initialHeight']));
		$obj->setOptions(notNull($arr['options']));

		if (@$arr['id']) $obj->setCustomOptions($arr['id']);

		return $obj;
	}
	
	public static function create($data, $obj) 
	{
		self::popupObjectFromArray($data, $obj);
		return $obj->save();		
	}
	public function save($data = array()) {

		$id = $this->getId();
		$type = $this->getType();
		$title = $this->getTitle();
		$options = $this->getOptions();

		global $wpdb;
		
		if($id  == '') {

				$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_popup(type,title,options) VALUES (%s,%s,%s)",$type,$title,$options);	
				$res = $wpdb->query($sql);
				

			if ($res) {
				$id = $wpdb->insert_id;
				$this->setId($id);
			}
			
			return $res;
			
		}
		else {
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_popup SET type=%s,title=%s,options=%s WHERE id=%d",$type,$title,$options,$id);
			$wpdb->query($sql);
			if(!$wpdb->show_errors()) {
				$res = 1;
			}

			return $res;
		}
	}
	public static function findAll($orderBy = null, $limit = null, $offset = null) {

		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."sg_popup";

		if ($orderBy) {
			$query .= " ORDER BY ".$orderBy;
		}

		if ($limit) {
			$query .= " LIMIT ".intval($offset).','.intval($limit);
		}

		//$st = $wpdb->prepare($query, array());
		$popups = $wpdb->get_results($query, ARRAY_A);

		$arr = array();
		foreach ($popups as $popup) {
			$arr[] = self::popupObjectFromArray($popup);
		}

		return $arr;
	}
	public static function delete($id) {
			$pop = self::findById($id);
			$type =  $pop->getType();
			$table = 'sg_'.$type.'_Popup';

			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."$table WHERE id = %d"
					,$id
				)
			);
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."sg_popup WHERE id = %d"
					,$id
				)
			);

			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."postmeta WHERE meta_value = %d and meta_key = 'wp_sg_popup'"
					,$id
				)
			);
	}
	
	public static function setPopupForPost($post_id, $popupId) {
		update_post_meta($post_id, 'wp_sg_popup' , $popupId);
	}
	
	
	public function render() {
		$parentOption = array('id'=>$this->getId(),'title'=>$this->getTitle(),'type'=>$this->getType(),'effect'=>$this->getEffect(),'width',$this->getWidth(),'height'=>$this->getHeight(),'delay'=>$this->getDelay(),'duration'=>$this->getEffectDuration(),'initialWidth',$this->getInitialWidth(),'initialHeight'=>$this->getInitialHeight());
		$getexrArray = $this->getExtraRenderOptions();
		$options = json_decode($this->getOptions(),true);
		if(empty($options)) $options = array();
		$sgPopupVars = 'SG_POPUP_DATA['.$this->getId().'] ='.json_encode(array_merge($parentOption, $getexrArray, $options)).';';

		return $sgPopupVars;
	}
	public static function getTotalRowCount() {
		global $wpdb;
		$res =  $wpdb->get_var( "SELECT COUNT(id) FROM ". $wpdb->prefix ."sg_popup" );
		return $res;
	}
	public static function getPagePopupId($page,$popup) {
		global $wpdb;
		$sql = $wpdb->prepare("SELECT meta_value FROM ". $wpdb->prefix ."postmeta WHERE post_id = %d AND meta_key = %s",$page,$popup);
		$row = $wpdb->get_row($sql);
		$id = (int)$row->meta_value;
		return $id;
	}
	
}

function notNull($param) {
	return ($param===null?'':$param);
}
