<?php

App::import('Vendor','mercadopago.mercadopago' ,array('file'=>'mercadopago'.DS.'lib'.DS.'mercadopago.php'));

class MercadopagoComponent extends Object {

	private $controller, $mp = NULL;
	private $preference_data, $preference = array();
	private $initialized, $started = false;
	private $access_token;

	function initialize(&$controller, $preference_data = array()) {
		$this->controller =& $controller;
		Configure::load('mercadopago.mercadopago');
		$client_id = Configure::read('Mercadopago.CLIENT_ID');
		$client_secret = Configure::read('Mercadopago.CLIENT_SECRET');
		if (empty($client_id) or empty($client_secret)){
			throw new Exception('Configure::read for Mercadopago.{CLIENT_ID, CLIENT_SECRET} are empty!');
		}else{
			$this->mp = new MP(Configure::read('Mercadopago.CLIENT_ID'), Configure::read('Mercadopago.CLIENT_SECRET'));
			$this->access_token = $this->mp->get_access_token();
			if (empty($this->access_token)){
				throw new Exception('Mercadopago access token is empty!');
			}
			$this->preference_data = $preference_data;
			$this->initialized = true;
		}
	}
	
	function startup(&$controller) {
		if (!$this->initialized){
			throw new Exception('MercadopagoComponent was not initialized');
		}else{
			$this->started = true;
		}
	}

	function getAccessToken(){
		return $this->access_token;
	}

	/**
	 * WHAT/WHY: 
	 *   Array Containing Checkout Preferences
	 *   http://developers.mercadopago.com/documentacion/api/preferences#glossary
	 * STRUCTURE:
	 * items: Listado de atributos correspondientes al item: 	Obligatoria
	 * - title: Título. Será mostrado en el proceso de pago. 	Obligatoria
	 * - quantity: Cantidad. 	Obligatoria
	 * - unit_price: Precio unitario. 	Obligatoria
	 * - currency_id: Tipo de moneda. Obligatoria 
	 * -> Argentina (Peso argentino): ARS, Brasil (Real): BRL, 
	 * -> México (Peso mexicano): MXN, Venezuela (Bolívar fuerte): VEF, 
	 * -> Colombia (Peso colombiano): COP
	 * - picture_url: URL de la imagen 	Opcional
	 * - id: Código 	Opcional
	 * - description: Descripción 	Opcional
	 * payer: Información del comprador: 	Opcional
	 * - name: Nombre del comprador 	Opcional
	 * - surname: Apellido del comprador 	Opcional
	 * - email: E-mail del comprador 	Opcional
	 * back_urls: URLs de retorno al sitio del vendedor: 	Opcional	
	 * - success: URL de pago acreditado 	Opcional
	 * - failure: URL de pago cancelado 	Opcional
	 * - pending: URL de pago pendiente 	Opcional
	 * payment_methods: Configuración de medios de pago a excluír en el proceso de pago: 	Opcional	
	 * - excluded_payment_methods, id: Identificador del medio de pago. Opcional
	 * - excluded_payment_types, id: Identificador del tipo de medio de pago. Opcional
	 * - installments: Cantidad máxima de cuotas que deseas aceptar con tarjeta de crédito. 	Opcional
	 * external_reference: Referencia que puedes utilizar para vincular el pago a tu sistema. 	Opcional
	 * collector_id: Tu identificador de vendedor MercadoPago 	-
	 * id: Identificador de la preferencia 	-
	 * init_point: URL de acceso al checkout 	-
	 * sandbox_init_point: URL de acceso al Sandbox de checkout
	 */
	function setPreference($preference_data){
		if (!$this->started){
			throw new Exception('MercadopagoComponent was not started');
		}else{
			$this->preference_data = array_merge($this->preference_data, $preference_data);
		}
	}

	function getInitPointURL($sandbox = true){
		if (!$this->started){
			throw new Exception('MercadopagoComponent was not started');
		}else{
			$this->preference = $this->mp->create_preference($this->preference_data);
			$sandbox_init = $sandbox ? 'sandbox_init_point' : 'init_point';
			return $this->preference['response'][$sandbox_init];
		}
	}
	
	function search($filters){
		$this->mp->search_payments($filters);
	}	
	
	function shutdown(&$controller){ return true; }
}

