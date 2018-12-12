<?php
	class Payment extends AppModel{
		var $name = 'Payment';
  		var $belongsTo = array('Competition','User');
		var $order = array("Payment.created" => "DESC");
	}
