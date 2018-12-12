<?php

echo "<h1>" . __("Cartelera de Mensajes", true) . "</h1>";
if ($paginator->counter(array('format'=>'%count%'))>10){
	echo "<p>";
	echo $paginator->numbers() . "&nbsp;";
	echo $paginator->prev('« Previa ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Siguiente »', null, null, array('class' => 'disabled'));
	echo "</p>";
}
?>
<div style='display:none;' id="messagedialog" title="<?php __("Mensaje"); ?>">
	<p align='left'>Cargado...</p>
</div>
<script language="Javascript">
  function showMessage(id){
    url="<?php echo Configure::read('Futbol.serverSslUrl')?>/Mails/message/id:"+id;
    $("#messagedialog").load(url).dialog({
			modal:true, height: 400, width: 800, maxHeight: 400, maxWidth: 800,
			close: function(){
        $('#messagedialog').html("<p align='left'>Cargado...</p>");
			}
		}); 
  }
</script>
<?php
echo "<table>";
echo "<tr>";
echo "<th>#</th>";
echo "<th>".$paginator->sort('Asunto', 'Mail.subject')."</th>";
echo "<th>".$paginator->sort('Fecha', 'Mailing.modified')."</th>";
echo "</tr>";
$i = $paginator->counter(array('format'=>'%count%'))
	-	$paginator->counter(array('format'=>'%start%')) + 1;
foreach($messages as $message){
	$Mailing = $message["Mailing"];
	$Mail = $message["Mail"];
	echo "<tr class='".($i%2==0?'row-a':'row-b')."'>";
	if ($Mailing["read"]==1){
		$style = "";
	}else{
		$style = "style='font-weight: bold;'";
	}
	$url="javascript:showMessage('".$Mail['id']."');";
	echo "<td><font $style>" . $this->Html->link($i--, $url) . "</font></td>";
	echo "<td><font $style>" . $this->Html->link($Mail["subject"], $url) . "</font></td>";
	echo "<td><font $style>" . $this->Html->link($Mailing["modified"], $url) . "</font></td>";
	echo "</tr>";
}
echo "</table>";

echo "<p>";
echo $paginator->counter(array(
	'format' => 'Página %page% de %pages%, mostrando %current% registros del %start% al %end% de %count% en total')
);
echo "</p>";


