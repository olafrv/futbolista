<?php

class MercadopagoHelper extends AppHelper {

  var $helpers = array(
		'Html'
		//,'Js'
		//,'Form'
		//,'Time'
	);

	/* WHAT/WHY: 
   *  Agrega estilos y comportamiento al botón de pago
   *  http://developers.mercadopago.com/documentacion/recibir-pagos
	 *  Para que el link tenga apariencia de botón, utiliza nuestro script render.js, 
	 *  que además te permitirá abrir el Checkout en una ventana modal dentro de tu propio sitio. 
	 */
	private function getRenderJs(){
		return $this->Html->script('https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js');
	}

/*
En el siguiente ejemplo, la función onreturn accionará diferentes mensajes según el collection_status
generalmente en el siguiente formato JSON:
{
  "back_url":"url-segun-failure-pending-o-success-configurada-en-la-preferencia-de-checkout" || null,
  "collection_id":"collection_id-creado-por-el-flujo-de-cobro" || null,
  "collection_status":"collection_status" || null,
  "external_reference":"external-reference-configurada-en-la-preferencia-de-checkout" || null,
  "preference_id":"preference-id-creado-en-la-preferencia-de-checkout"
}
La variable collection_status puede tener los siguientes valores:
- approved	El pago fue aprobado y acreditado
- pending	El usuario no completó el pago
- in_process	El pago está siendo revisado
- rejected	El pago fué rechazado, el usuario puede intentar nuevamente el pago
- null	El usuario no completó el proceso de pago y no se ha generado ningún pago
*/
	function getOnReturnJs($js_function_name = 'execute_my_onreturn'){
		return $this->Html->scriptBlock("
function $js_function_name (json) {
  if (json.collection_status=='approved'){
    alert ('Pago acreditado');
  } else if(json.collection_status=='pending'){
    alert ('El usuario no completó el pago');
  } else if(json.collection_status=='in_process'){    
    alert ('El pago está siendo revisado');    
  } else if(json.collection_status=='rejected'){
    alert ('El pago fué rechazado, el usuario puede intentar nuevamente el pago');
  } else if(json.collection_status==null){
    alert ('El usuario no completó el proceso de pago, no se ha generado ningún pago');
  }
}
");
	}

 /**
  * $type = button, jswindow, iframe
  * $init_point_href = result from calling MercadoPagoComponent->getHref()
  * $properties = array of the follwing key->val:
  *  - name = the name of the link
  *  - class = "color-tamaño-forma-fuente-logo"
  *    - color: blue, orange, red, green, lightblue, grey
  *    - tamaño: L (large), M (medium), S (small)
  *    - forma: Sq (square), Rn (rounded), Ov (oval)
  *    - fuente: Ar (Arial), Tr (Trebuchet), Ge (Georgia)
  *    - logo: 
  *      - Argentina: ArAll, Brasil: BrAll, México: MxAll, Venezuela: VeAll Colombia: CoAll
  *        (Logos de todos los medios de pago)
  *      - Argentina: ArOn, Brasil: BrOn, México: MxOn, Venezuela: VeOn, Colombia: CoOn
  *        (Logos de medios de pago de acreditación instantánea) 
  * mp-mode:  modal (ventana modal), popup (ventana pop-up),
	*						blank (nueva ventana o pestaña), redirect (misma ventana) 
  * onreturn: Recibe comunicación del Checkout cuando el usuario finaliza el proceso de pago
  *           o cierra la ventana (modal). Los modos iframe, popup, blank y redirect no permiten
  *           esta comunicación.
  */
	function getPayUI($type, $init_point_href, $title, $properties=array()){
		if (empty($init_point_href)){
			throw new Exception('Mercadopago init point URL is empty!');
		}
		if (isset($properties["name"])) unset($properties["name"]);
		$properties = array_merge(array(
				'name'=>'MP-Checkout', //Obligatorio
				'class'=>'lightblue-M-Ov-ArOn',
				'mp-mode'=>'modal',
				'onreturn'=>'execute_my_onreturn'
		), $properties);
		if ($type=='button'){
			$html = $this->Html->link($title, $init_point_href, $properties);
		}else if ($type=='jswindow'){
			$js = "\$MPC.openCheckout {url: '$init_point_href', mode: '".$properties['mp-mode']."',";
			if ($properties["mp-mode"] == 'modal'){
				$js .= "onreturn: function(data) { " . $properties["onreturn"] . "(data) }";
			}
			$html = $this->Html->scriptBlock($js);
		}else if ($type=='iframe'){
			$html = "<iframe src='[init_point]' name='MP-Checkout' width='$properties[width]' height='$properties[height]' frameborder='0'></iframe>";
		}else{
			$html = "<!-- Unknown PayUI type '$type'-->";
		}	
		return $html . $this->getRenderJs();
	}	

}
