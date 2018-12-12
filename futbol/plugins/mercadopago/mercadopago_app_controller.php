<?php
class MercadopagoAppController extends AppController {

	function beforeFilter(){
		parent::beforeFilter();
		Configure::load('mercadopago.mercadopago');
	}
	
}
