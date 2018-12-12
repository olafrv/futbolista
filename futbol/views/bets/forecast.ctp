<?php
   echo "<h1>" . __(" Todas las Predicciones", true) . "</h1>";

		echo $this->element("my-menu");

?>
<div align="center">
<?php
	$header = "";
	echo $this->FutbolGui->makeMatchForm(
		$competition_list, $competition_id, 
			$fase_list, $fase_id, $groupping_list, $groupping_id, 
				$match_list, $match_id, 'Bet', 'forecast', $header);
	echo '<br/>';
	echo '<br/>';
   echo $this->Html->image('/img/preppy.png', array('border'=>0, 'class'=>'borderless'));
  	echo '&nbsp;' . $this->Form->checkbox('showperuser', 
		array(
			'value' => '1',
         'onchange' => "javascript:f=Jolaf.gebi('BetForm'); f.submit();"
		)
	);
	echo '<font style="color: red; font-weight: bold;">&nbsp;' .  
				__('¿Mostrar por usuario?', true) . '</font>&nbsp;&nbsp;';
	echo $this->Form->end(NULL);

?>
	<h3>
		<?php
			if (is_null($bet_score)) {
				echo __('Total de predicciones:',true) . ' <strong>' . $bet_count . '</strong>';
			}else{
				echo __("Predicciones con Resultado = ", true) . "&nbsp;" . $bet_score[0] .' - ' . $bet_score[1] ;
			}
      ?>
   </h3>

<p>
<?php if (is_null($bet_score)): ?>
<b>Nota</b>: Haga clic sobre el resultado para ver quién tiene dicha predicción.<br/>
<?php endif; ?>
<?php if ($showperuser): ?>
<b>Nota</b>: Haga clic sobre el usuario para ver sus todas predicciones.
<?php endif; ?>
</p>

<?php if (count($bet_table) > 0){ ?>
	<table>
		<tr>
			<?php
				if ($showperuser){
					echo '<th>#</th>';
					echo '<th>' . __('Usuario', true) . '</th>';
					if (is_null($bet_score)) echo '<th>' . __('Predicción', true) . '</th>';
				}else{
					echo '<th>' . __('Predicción', true) . '</th>';
					echo '<th>' . __('Porcentaje', true) . '</th>';
				}
			?>
		</tr>
<?php
	$i = 1; //Counter when showed per user
	foreach ($bet_table as $row) {

		$bet_host_goals = isset($row["Bet"]["host_goals"]) ? $row["Bet"]["host_goals"] : "";	 
		$bet_guest_goals = isset($row["Bet"]["guest_goals"]) ? $row["Bet"]["guest_goals"] : "";	 

		$percentage = $row[0]["percentage"];	
	
		if ($showperuser){
			$cells = array();
			$cells[0][0] = $i++;
			$cells[0][1] = 
					array(
						$this->Html->link($row["User"]["username"], '/bets/mine/user:' . $row['User']['id'] . '/competition:'. $competition_id),
						array('class'=>'center')
					);
			if (is_null($bet_score)) $cells[0][2] =
					array(
							/*$bet_host_goals . ' - ' . $bet_guest_goals, 
							array('class'=>'center')*/
							$this->Html->link(
								$bet_host_goals . ' - ' . $bet_guest_goals, 
								"javascript:f=Jolaf.gebi('BetForm'); f.action='"
								. "forecast/bet_score:" .	$bet_host_goals . '-' . $bet_guest_goals . "';" 
								. "c = Jolaf.gebi('BetShowperuser'); c.checked=true;"
								. "f.submit();"
							),	
							array('class'=>'center')
					);
		}else{
			$cells =	
				array(
					array(
						//array($bet_host_goals . ' - ' . $bet_guest_goals, array('class'=>'center')),
						array(
							$this->Html->link(
								$bet_host_goals . ' - ' . $bet_guest_goals, 
								"javascript:f=Jolaf.gebi('BetForm'); f.action='"
								. "forecast/bet_score:" .	$bet_host_goals . '-' . $bet_guest_goals . "';" 
								. "c = Jolaf.gebi('BetShowperuser'); c.checked=true;"
								. "f.submit();"
							),	
							array('class'=>'center')
						),
						array(round($percentage,2) . '%', array('class'=>'center'))
					)
				);
		}
		
		echo $this->Html->tableCells($cells, array('class'=>'row-a'), array('class'=>'row-b'));
		
	}
?>
	</table>
<?php } ?>
</div>
