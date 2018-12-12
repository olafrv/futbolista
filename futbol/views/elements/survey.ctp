<?php

$token = $this->requestAction('/Limetokens/getToken');

if (!is_null($token)){

	$survey = Configure::read('Futbol.lastSurvey');

?>
<table align="center">
	<tr>
		<td>
			<?php echo $this->Html->image('/img/survey.png', array('class'=>'borderless')); ?>
		</td>
		<td>
			<div class="ui-widget">
			   <div class="ui-state-highlight ui-corner-all" style="margin-left: auto; margin-right: auto; padding: 0 .7em;">
			      <p>
						<b>IMPORTANTE: </b>Si te ha gustado esta página Web es importante que 
						llenes ésta <b>encuesta</b> haciendo
						<?php 
								echo $html->link(
									"[Clic Aquí]", 
									"https://www.olafrv.org/limesurvey/index.php?lang=es&sid=$survey&token=$token",array('target'=>'_blank', 'style'=>'font-weight: bold;')); 
						?>.
						Ahí podrás escoger las próximas competencias, partidos, montos de
						las contribuciones y opinar sobre el sistema.
			      </p>
				</div>
			</div>
		</td>
	</tr>
</table>

<?php 

}

?>
