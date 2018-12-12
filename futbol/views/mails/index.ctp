<?php 

$mailIsHtml = !isset($this->data["Mail"]["html"]) || (isset($this->data["Mail"]["html"]) && $this->data["Mail"]["html"]==1);

if ($mailIsHtml){
	echo $this->Html->script('tinymce/js/tinymce/tinymce.min.js');
	echo $this->Html->scriptBlock(
"tinymce.init({
    selector: 'textarea'
    , plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste'
    ]
		, toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
		, convert_urls: false
});"
);
}

echo "<h1>Registro de Correo Electrónico</h1>";

echo "<p>";
echo " Aquí se registran los correos electrónicos que posteriormente";
echo " son construídos por el cronjob shell <b>bake-futbol.sh mailFutbolista</b>";
echo " enviando <b>" . Configure::read('Futbol.smtpMaxMails') . " correos electrónicos</b> (Futbol.smtpMaxMails, /config/core.php)</b>";
echo " cada vez que se ejecuta.";
echo " El envío masivo puede ejecutarse manualmente con un <b>máximo de " . ini_get ('max_execution_time') . " segundos (php.ini)</b>";
echo " de ejecución hacer clic " . $this->Html->link('aquí', '/Mails/send') . ".</p>";

echo $this->Form->create('Mail',array('id'=>'MailForm'));
echo $this->Form->input('subject',array('type'=>'text', 'maxlength'=>255, 'label'=>'Asunto'));
echo $this->Form->input('body',array('type'=>'textarea', 'rows'=>'10', 'label'=>'Mensaje'));

$url = Router::url(array('controller' => 'Mails', 'action' => 'index'));
echo $this->Form->input('html',array(
		'type'=>'checkbox', 'value'=>'1', 'checked'=>$mailIsHtml,
    'label' => false,
    'div' => false,
    'onchange' => "javascript:f=Jolaf.gebi('MailForm');f.action='$url'; f.submit();"
));
echo " Formato HTML";

echo "<br><br>";
echo $this->Form->button('Guardar', array(
		'type'=>'button', 'class' => 'button-green',
		'onclick' =>	"javascript:f=Jolaf.gebi('MailForm');f.action='".Router::url($action)."'; f.submit();",
));
echo "&nbsp;";
echo $this->Form->button('Deshacer Cambios', array('type'=>'reset', 'class' => 'button-yellow'));
echo "&nbsp;";
$url = Router::url(array('controller' => 'Mails', 'action' => 'index', 'todo'=>'create'));
echo $this->Form->button('Guardar Como Nuevo', array(
	'type'=>'button', 'class' => 'button-green',
	'onclick' =>	"javascript:f=Jolaf.gebi('MailForm');f.action='$url'; f.submit();",
));

echo $this->Form->end();	

if (!empty($mails)){
	echo "<table><tbody>";
  echo "<tr>";
  echo "<th>Título (Id)</th>";
  echo "<th>Mensaje</th>";
  echo "<th>Operaciones</th>";
  echo "</tr>";
  $i=0;				
	foreach($mails as $mail){
		$Mail = $mail["Mail"];
		echo "<tr class='".($i++%2==0?"row-a":"row-b")."'>";
		echo "<td>" . $Mail["subject"] . " (" . $Mail["id"] . ")" . "</td>";
		echo "<td><pre>" . substr($Mail["body"],0,100) . "</pre><b>(...)</b></td>";
		echo "<td>";
		$url = Router::url(array('controller' => 'Mails', 'action' => 'index', 'todo'=>'edit','id' => $Mail["id"]));
		echo $this->Form->button('Editar', array(
				'type'=>'button', 'class' => 'button-green',
				'onclick' =>	"javascript:f=Jolaf.gebi('MailForm');f.action='$url'; f.submit();",
		));
		$url = Router::url(array('controller' => 'Mails', 'action' => 'index', 'todo'=>'delete', 'id' => $Mail["id"]));
		echo "&nbsp;";
		echo $this->Form->button('Eliminar', array(
				'type'=>'button', 'class' => 'button-red',
				'onclick' =>	"javascript:if(prompt('Escriba ELIMINAR y presione enter')=='ELIMINAR') f=Jolaf.gebi('MailForm');f.action='$url'; f.submit();",
		));
		echo "</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
}else{	
	echo $this->element('flash_ok', array('message'=>'No hay correo electrónicos pendientes por enviar.'));
}
