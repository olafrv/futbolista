<?php

/** 
 * PLUGIN CONTROLLER ACTION - URL:
 * http[s]://<server>[/<cakeapp>]/mercadopago/MercadopagoPayments/<action> 
 */

class MercadopagoPaymentsController extends MercadopagoAppController {

	var $helpers = array('mercadopago.Mercadopago');
	var $components = array('mercadopago.Mercadopago');

	function beforeFilter() {
		parent::beforeFilter();
		if (Configure::read('debug')>0){
			$this->Auth->allow(array('notify','pay', 'accesstoken'));
		}
	}

	function accesstoken(){
		$this->set('access_token', $this->Mercadopago->getAccessToken());
	}

	function pay(){
	
		// Payment Configuration
		$preference = array(
		  "items" => array(
         array(
           "id" => "www.olafrv.info/futbol",
		       "title" => "Servicio Web",
		       "quantity" => 1,
		       "unit_price" => 100.00,
		       "currency_id" => "VEF",
	         "picture_url"=> "https://www.olafrv.info/futbol/img/futbol.ico"
		     )
		  )
		  , "payer" => array(
				"name" => "Olaf",
				"surname" => "Reitmaier",
				"email" => "olafrv@gmail.com"
		  )
	    ,"back_urls" => array(
  			"success" => "https://www.success.com",
        "failure" => "http://www.failure.com",
        "pending" => "http://www.pending.com"
    	),
		);
		$this->Mercadopago->setPreference($preference);
		
		// Payment Visualization
		$this->set('properties', array());
//		$init_point_href = "https://www.mercadopago.com/mlv/checkout/pay?pref_id=75985616-d179ce5d-5dec-4bb2-b113-9c629bfb5ad8";
//		$this->set('init_point_href', $init_point_href);
		$this->set('init_point_href', $this->Mercadopago->getInitPointURL(true));

	}

	/**
   **************************************************************************
   * MERCADOPAGO Configured IPN 2.0 Notification URL must be this:          *
   *   http[s]://<server>[/<cakeapp]/mercadopago/MercadopagoPayments/notify *
   **************************************************************************
   * WHAT/WHY:
	 *   https://developers.mercadopago.com/documentacion/notificaciones-de-pago
	 * PARAMS (GET):
	 *   topic = tipo-de-notificación, por defecto 'payment'
	 *   id = identificador-de-notificación-de-pago
	 */
	function notify(){
			if (isset($params["named"]["topic"]) && $params["named"]["topic"]=="payment"){
				if (isset($params["named"]["id"])){
						
				}
			}else{
				$this->log('Mercadopago: null or invalid value for parameter topic for notification');
			}
	}

	/**
   * WHAT/WHY: 
	 *   https://developers.mercadopago.com/documentacion/busqueda-de-pagos-recibidos
   *   Fields and search filters detailed in mercadopago/models/MercadopagoPaymentModel
	 */
	function get(){
		/*
 curl -X GET \
-H 'accept: application/json' \
'https://api.mercadolibre.com/collections/id-del-pago?access_token=tu_access_token'
		*/
	}

	/**
   * WHAT/WHY: 
	 *   https://developers.mercadopago.com/documentacion/busqueda-de-pagos-recibidos
	 *   Fields and search filters detailed in mercadopago/models/MercadopagoPaymentModel
	 */
	function search(){
    $filters = $this->data["Filters"];
    $result = $this->Mercadopago->search($filters);
		$this->set('result',$result);
	}

}
