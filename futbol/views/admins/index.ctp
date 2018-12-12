<?php //Admin View

$links = array(
	array('/Competitions',__('Competitions',true),'competition.png'),
	array('/Stadia',__('Stadia',true),'stadia.png'),
	array('/Urls',__('Urls',true),'url.png'),
	array('/Fases',__('Fases',true),'fase.png'),
	array('/Grouppings',__('Grouppings',true),'groupping.png'),
	array('/PseudoTeams',__('PseudoTeams',true),'pseudoteam.png'),
	array('/Teams',__('Teams',true),'team.png'),
	array('/Matches',__('Matches',true),'match.png'),	
	array('/Payments/reportPayments',__('Payments',true),'payment.png'),
  array('/Users',__('Users',true),'user.png'),
  array('/Users/acls',__('ACLs',true),'user-login.png'),
  array('/Photos/add',__('Photos',true),'picture.png'),
  array('/Mails/index',__('Mails',true),'Email-icon.png'),
  array('/Admins/phpinfo',__('PHP Info',true),'php.png'),
	array(Configure::read('Futbol.surveyUrl'),__('Survey',true),'survey.png')
);
?>
<h1>Panel de Administración</h1>
<table border="0" cellpadding="10" cellspacing="10" style="align: center;">
<?php
for($i=0;$i<count($links);$i++){
	if ($i%5==0){
		if ($i>0) echo "</tr>";
		echo "<tr>";
	}
	echo "<td style='text-align: center;'>";
	echo $this->Html->link(
		$this->Html->image(
			'/img/' . $links[$i][2], array('class'=>'borderless', 'width'=>'75px')
		) . "<br/>" . $links[$i][1],	
		$links[$i][0],	
		array('escape' => false, 'target'=>'_blank')
	);
	echo "<br/>";
  echo "<b>".($i+1)."&ordm;<b>";
	echo "<br/>";
	echo "<br/>";
	echo "</td>";
}
echo "</tr>";
?>
</table>

<h1>Envío de Mensaje Masivo (De Madrugada)</h1>

<p>
La generacion del mensaje de correo electrónico diario con los
resultados de cada jornada puede ser generado por:

<ul>
	<li>
		Solicitud <b>Web (Manual)</b>:
		<br>
		<ol>
		<li>Generar el contenido del correo electrónico en: 
		<?php
		  $serverUrl = Configure::read('Futbol.serverSslUrl');
		  echo $this->Html->link($serverUrl . '/Bets/futbolista', $serverUrl . '/Bets/futbolista', array('target'=>'_blank'));
		?>
		</li>
		<li>Luego, cree (Copiar y Pegar) el texto (HTML) en un nuevo correo electrónico en 
		  <?php echo $this->Html->link('/admins/mails', '/admins/mails', array('target'=>'_blank')); ?>
		</li>
		</ol>
	</li>
	<li>
		Solicitud <b>Web Service (Cronjob)</b>: 
		<code>
			cd /home/olafrv/cakeapp/futbol/;./bake-futbolista.sh "<?php echo $serverUrl; ?>"
		</code>
	</li>
	<li>
		Solicitud <b>Cake Shell (Cronjob)</b>, es un método OBSOLETO:
		<code>
			cd /home/olafrv/cakeapp/futbol/;./bake-futbol.sh mailFutbolista;
		</code>
	</li>
</p>


