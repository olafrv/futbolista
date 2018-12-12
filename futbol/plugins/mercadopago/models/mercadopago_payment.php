<?php

class MercadopagoPayment extends MercadopagoAppModel {
/*

https://developers.mercadopago.com/documentacion/busqueda-de-pagos-recibidos

Filtros de búsqueda:

	id: 	Identificador del pago.
	site_id: 	Identificador de país. Argentina: MLA; Brasil: MLB. México: MLM. Venezuela: MLV. Colombia: MCO.
	date_created: 	Fecha de creación. Ej: range=date_created&begin_date=NOW-1DAYS&end_date=NOW (Ver ISO-8601)
	date_approved: 	Fecha de aprobación. Ej: range=date_approved&begin_date=NOW-1DAYS&end_date=NOW (Ver ISO-8601)
	last_modified:	Fecha de última modificación. Ej: range=last_modified&begin_date=NOW-1DAYS&end_date=NOW (Ver ISO-8601)
	money_release_date:	Fecha de liberación del cobro. Ej: range=money_release_date&begin_date=NOW-1DAYS&end_date=NOW (Ver ISO-8601)
	payer_id: 	Identificador del comprador.
	reason: 	Descripción de lo que se está pagando.
	transaction_amount:	Valor de la transacción.
	currency_id: 	Tipo de moneda. 
	 - Argentina: ARS (Peso argentino); 
	 - Brasil: BRL (Real); 
	 - México: MXN (Peso Mexicano); 
	 - Venezuela: VEF (Bolívar fuerte); 
	 - Colombia: COP (Peso colombiano).
	external_reference: 	Referencia que puedes utilizar para vincular el pago a tu sistema.
	mercadopago_fee: 	Comisión por el uso de MercadoPago.
	net_received_amount: 	Monto que recibe el vendedor sin incluir el mercadopago_fee.
	total_paid_amount:	Monto total obtenido de la suma de los siguientes atributos: 
	  transaction_amount, shipping_cost y el monto que pagó el comprador 
	  (incluyendo la financiación para tarjetas de crédito).
	shipping_cost: 	Monto de envío.
	status: 	Estado del pago
	 - pending: 	El usuario no completó el proceso de pago.
	 - approved: 	El pago fue aprobado y acreditado.
	 - in_process: 	El pago está siendo revisado.
	 - rejected: 	El pago fue rechazado. El usuario puede intentar nuevamente.
	 - cancelled: 	El pago fue cancelado por superar el tiempo necesario para realizar el pago o por una de las partes.
	 - refunded: 	El pago fue devuelto al usuario.
	 - in_mediation: 	Se inició una disputa para el pago.
	 - status_detail: 	Detalle del estado del cobro.
	released: 	Disponibilidad del monto a cobrar. Valores posibles: yes, no.
	operation_type: 	Tipo de operación
	regular_payment: 	Pago.
	money_transfer: 	Envío de dinero.
	recurring_payment: 	Pago recurrente por suscripción activa.
	subscription_payment: 	Pago junto a inicio de suscripción.

Parámetros de paginado:

	limit 	Cantidad de registros que se requieren (valor máximo = 50). Si no se define, devuelve hasta 30 registros encontrados. Ej: 12.
	offset 	Posición a partir de la cual se desea que devuelvan los registros. Por defecto el valor es 0 (máximo permitido: 10000).
	sort 	Establece un criterio a partir del cual se ordenan los resultados. Ej: sort=external_reference, sort=date_created, sort=status, etc.
	criteria 	Orden de los datos. Puede ser asc (ascendente) o desc (descendente).

*/

	var $name = 'MercadopagoPayment';
	var $belongsTo = array('User');
	var $useTable = false;
}
