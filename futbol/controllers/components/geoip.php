<?php

App::import('Vendor','Geoip' ,array('file'=>'geoip'.DS.'geoip.inc'));

define('GEOIP_CAKEPHP_VENDOR_DIR', APP . DS . 'vendors' . DS . 'geoip');
define('GEOIP_CAKEPHP_DAT_FILE', GEOIP_CAKEPHP_VENDOR_DIR . DS . 'GeoIP.dat');

class GeoipComponent extends Object {

	private $controller;
	private $geoip;

	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		$this->geoip = geoip_open(GEOIP_CAKEPHP_DAT_FILE, GEOIP_STANDARD);
	}
	
	function startup(&$controller) {}

	function country_code_by_addr($ipv4){
		return geoip_country_code_by_addr($this->geoip, $ipv4);
	}

	function shutdown(&$controller){
		geoip_close($this->geoip);
	}
}

