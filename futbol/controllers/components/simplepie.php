<?php

App::import('Vendor','Simplepie' ,array('file'=>'simplepie'.DS.'autoloader.php')); 

class SimplepieComponent extends Object {

	var $simplepie;
	var $controller;

	function startup( &$controller ) {
		$this->controller =& $controller;
		$this->simplepie = new SimplePie();
   }

	function fetch($url, $items=0, $reverse=false){
		$rss = array();
		$this->simplepie->set_feed_url($url);
		$this->simplepie->set_cache_location(TMP);
		$this->simplepie->init();
		$this->simplepie->handle_content_type();
		$i = 0;
		foreach ($this->simplepie->get_items() as $item){
			$enclosure = $item->get_enclosure();
 			$rss[$i++] = array(
				'link' => $item->get_permalink(),
				'title' => $item->get_title(),
				'description' => $item->get_description(),
				'date' => $item->get_date('U'), //Unix Epoch Seconds
				'enclosure' => ($enclosure ? array(
					'type' => $enclosure->get_type(),
					'link' => $enclosure->get_link()
				) : NULL)
			);
			if ($items>0 && $i>$items) break;
 	   }	
		return $rss;
	}

}

