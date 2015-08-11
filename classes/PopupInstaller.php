<?php
class PopupInstaller {
	public static function creteTable() { 
		global $wpdb;
		$sg_popup_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix ."sg_popup (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(255) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `options` text NOT NULL,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$sg_popup_image_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix ."sg_image_popup (
		  	`id` int(11) NOT NULL,
  			`url` varchar(255) NOT NULL
		) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;";
		$sg_popup_html_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix ."sg_html_popup (
		  	`id` int(11) NOT NULL,
  			`content` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
	
		$wpdb->query($sg_popup_base);
		$wpdb->query($sg_popup_image_base);
		$wpdb->query($sg_popup_html_base);
	}
	public static function createTables($bolgs_id) { 
		global $wpdb;
		update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
		$sg_popup_net_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix.$bolgs_id."_sg_popup (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(255) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `options` text NOT NULL,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$sg_popup_image_net_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix.$bolgs_id."_sg_image_popup (
		  	`id` int(11) NOT NULL,
  			`url` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$sg_popup_html_net_base = "CREATE TABLE IF NOT EXISTS  ". $wpdb->prefix.$bolgs_id."_sg_html_popup (
		  	`id` int(11) NOT NULL,
  			`content` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
	
		$wpdb->query($sg_popup_net_base);
		$wpdb->query($sg_popup_image_net_base);
		$wpdb->query($sg_popup_html_net_base);
	}

	public static function install() {
		$obj = new self();

		$obj->creteTable();
		if(is_multisite() ) {

			$stites = wp_get_sites();
			foreach($stites as $site) {
				$bolgs_id = $site['blog_id'];
				global $wpdb;
				$obj->createTables($bolgs_id);
			}
		}
	}
	public static function uninstallTabele() {
		global $wpdb;
		$delete = "DELETE  FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'sg_promotional_popup' ";
		$wpdb->query($delete);
		$popup_table = $wpdb->prefix."sg_popup";
		$popup_sql = "DROP TABLE ". $popup_table;
		$popup_image_table = $wpdb->prefix."sg_image_popup";
		$popup_image_sql = "DROP TABLE ". $popup_image_table;
		$popup_html_table = $wpdb->prefix."sg_html_popup";
		$popup_html_sql = "DROP TABLE ". $popup_html_table;

		$wpdb->query($popup_sql);
		$wpdb->query($popup_image_sql);
		$wpdb->query($popup_html_sql);

	}
	public static function uninstallTabeles($bolgs_id) {
		global $wpdb;
		$delete = "DELETE  FROM ".$wpdb->prefix.$bolgs_id."_postmeta WHERE meta_key = 'sg_promotional_popup' ";
		$wpdb->query($delete);
		$popup_net_table = $wpdb->prefix.$bolgs_id."_sg_popup";
		$popup_net_sql = "DROP TABLE ". $popup_net_table;
		$popup_image_net_table = $wpdb->prefix.$bolgs_id."_sg_image_popup";
		$popup_image_net_sql = "DROP TABLE ". $popup_image_net_table;
		$popup_html_net_table = $wpdb->prefix.$bolgs_id."_sg_html_popup";
		$popup_html_net_sql = "DROP TABLE ". $popup_html_net_table;

		$wpdb->query($popup_net_sql);
		$wpdb->query($popup_image_net_sql);
		$wpdb->query($popup_html_net_sql);

	}
	public static function uninstall() {
		global $wpdb;	//required global declaration of WP variable

		$obj = new self();

		$obj->uninstallTabele();
		if(is_multisite() ) {

			$stites = wp_get_sites();
			foreach($stites as $site) {
				$bolgs_id = $site['blog_id'];
				global $wpdb;
				$obj->uninstallTabeles($bolgs_id);
			}
		}
		
	}
		public function covertPromotionalTable() {
		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_promotional_popup", array());
		$popups = $wpdb->get_results($st, ARRAY_A);
		foreach ($popups as $popup) {
			$options = $popup['options'];
			$jsonData = json_decode($options);
			$title = $jsonData->title;
			$type = strtolower($popup['content']);
			$mainsql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_popup(type,title,options) VALUES (%s,%s,%s)",$type,$title,$options);	
			$resmain = $wpdb->query($mainsql);
			$id = $popup['id'];
			$sgHtmlPopup = $popup['html'];
			$sgImagePopup = $popup['image'];
			$sgIframePopup = $popup['iframe'];
			$sgShortCodePopup = $popup['shortCode'];
			switch ( $popup['content']) {
				case 'iframe':
					$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_iframe_popup (id, url) VALUES (%d,%s)",$id,$sgIframePopup);	
					$res = $wpdb->query($sql);
					break;
				case "Image":
					$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_image_popup (id, url) VALUES (%d,%s)",$id,$sgImagePopup);	
					$res = $wpdb->query($sql);
					break;
				case "html":
					$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_html_popup (id, content) VALUES (%d,%s)",$id,$sgHtmlPopup);	
					$res = $wpdb->query($sql);
					break;
				case "shortCode":
					$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_shortcode_popup (id, url) VALUES (%d,%s)",$id,$sgShortCodePopup);	
					$res = $wpdb->query($sql);
					break;
			}
		}					
	}

	public function covertPromotionalTables($bolgs_id) {
		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix.$bolgs_id."_sg_promotional_popup", array());
		$popups = $wpdb->get_results($st, ARRAY_A);
		foreach ($popups as $popup) {
			$options = $popup['options'];
			$jsonData = json_decode($options);
			$title = $jsonData->title;
			$type = strtolower($popup['content']);
			$mainsqlnet = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix.$bolgs_id."_sg_popup(type,title,options) VALUES (%s,%s,%s)",$type,$title,$options);	
			$resmain = $wpdb->query($mainsqlnet);
			$id = $popup['id'];
			$sgHtmlPopup = $popup['html'];
			$sgImagePopup = $popup['image'];
			$sgIframePopup = $popup['iframe'];
			$sgShortCodePopup = $popup['shortCode'];
			switch ( $popup['content']) {
				case 'iframe':
					$sqlnet = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix.$bolgs_id."_sg_iframe_popup (id, url) VALUES (%d,%s)",$id,$sgIframePopup);	
					$res = $wpdb->query($sqlnet);
					break;
				case "Image":
					$sqlnet = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix.$bolgs_id."_sg_image_popup (id, url) VALUES (%d,%s)",$id,$sgImagePopup);	
					$res = $wpdb->query($sqlnet);
					break;
				case "html":
					$sqlnet = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix.$bolgs_id."_sg_html_popup (id, content) VALUES (%d,%s)",$id,$sgHtmlPopup);	
					$res = $wpdb->query($sqlnet);
					break;
				case "shortCode":
					$sqlnet = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix.$bolgs_id."_sg_shortcode_popup (id, url) VALUES (%d,%s)",$id,$sgShortCodePopup);	
					$res = $wpdb->query($sqlnet);
					break;
			}
		}
	}
	public static function convert() {
		global $wpdb;

		$obj = new self();
		
		$obj->covertPromotionalTable();
	
		if(is_multisite()) {
			$stites = wp_get_sites();
			foreach($stites as $site) {
				$bolgs_id = $site['blog_id'];
				global $wpdb;
				
				$obj->covertPromotionalTables($bolgs_id);
			}
		}
	}
}