<?php
	echo "<h1>Clasificación/Posición de Equipos</h1>";
	echo $this->element("my-menu");
	$i = 0;
?>
<div align="center">
	<p>
		<strong><?php __('Leyenda'); ?>:</strong> #=Posición, J=Jugados, G=Ganados, P=Perdidos, 
			E=Empatados,<br/> Anotaciones (F=A Favor, C=En Contra, D=Diferencia), Pt=Puntos
	</p>   
<?php

	$header = '';
	echo $this->FutbolGui->makeGrouppingForm($competition_list, $competition_id, 
		$fase_list, $fase_id, $groupping_list, $groupping_id, false, 'Ranking', 'show', $header);
   echo "&nbsp;" . $this->Form->checkbox('homeaway', 
		array(
			'value' => '1',				
			'onchange' => "javascript:f=Jolaf.gebi('RankingForm'); f.submit();"
		)
	);
 	echo " " . __('Mostrar Anfitrión/Visitante', true);
	echo $this->Form->end(NULL);

	if (count($ranking_table)>0){

?>
	<table>
		<tr>
			<th colspan="3">&nbsp;</th>
<?php
		$colspan=6; if ($competition_sport == 'Beisbol') --$colspan;
		if ($homeaway){
			echo "<th colspan='$colspan'>" . __('Anfitrión', true) . '</th>';
			echo "<th colspan='$colspan'>" . __('Visitante', true) . '</th>';
		}else{
			echo "<th colspan='$colspan'>" . __('Estadísticas', true) . '</th>';
		}
		if ($competition_sport == 'Beisbol'){
			echo '<th colspan="3">' . __('Totales', true) . '</th>';
		}else{
			echo '<th colspan="4">' . __('Totales', true) . '</th>';
		}	
?>
    </tr>
		<tr>
			<th>#</th>
			<th><?php echo __('Equipo', true); ?></th>
			<th><?php echo __('J', true); ?></th>
			
			<th><?php echo __('G', true); ?></th>
<?php
	if ($competition_sport != 'Beisbol'){
			echo '<th>' . __('E', true) . '</th>';
	}
?>
			<th><?php echo __('P', true); ?></th>
			<th><?php echo __('F', true); ?></th>
			<th><?php echo __('C', true); ?></th>
			<th><?php echo __('D', true); ?></th>

<?php 

	if ($homeaway){ 

?>
		
			<th><?php echo __('G', true); ?></th>
<?php
	if ($competition_sport != 'Beisbol'){
			echo '<th>' . __('E', true) . '</th>';
	}
?>
			<th><?php echo __('P', true); ?></th>
			<th><?php echo __('F', true); ?></th>
			<th><?php echo __('C', true); ?></th>
			<th><?php echo __('D', true); ?></th>

<?php } ?>

			<th><?php echo __('D', true); ?></th>
			<th><?php echo __('PT', true); ?></th>
<?php
		if ($competition_sport == 'Beisbol'){
			echo '<th>' . __('G/J', true) . '</th>';
		}
?>
		</tr>
<?php

	foreach ($ranking_table as $row) {

		$team = isset($row["PseudoTeam"]["Team"]["title"]) ?
			$row["PseudoTeam"]["Team"]["title"] : $row["PseudoTeam"]["abreviation"];

    $team_flag = isset($row["PseudoTeam"]["Team"]["Country"]["code"]) ?
      $row["PseudoTeam"]["Team"]["Country"]["code"] : "";

    $team_shield = $row["PseudoTeam"]["Team"]["has_shield"]==1 ?
      $row["PseudoTeam"]["Team"]["abreviation"] : "";

    $team_flag = $team_flag != "" ? $this->Html->image('/img/flags/' .
      $team_flag . '.gif', array('border'=>0, 'class'=>'borderless')) : "";

    $team_shield = $team_shield != "" ? $this->Html->image('/img/shields/' .
      $team_shield . '.png', array('border'=>0, 'class'=>'borderless')) : "";

    $team_symbol = ($team_shield!="" ? $team_shield : $team_flag);

		$team_symbol .= ' ' . $team;

		$ranking = $row['Ranking'];

		$rows = array();

		$rows[] = array($i+1,array('class'=>'nw center'));
		$rows[] = array($team_symbol, array('class'=>'nw'));
		$rows[] = array($ranking['played'],array('class'=>'nw center'));
	
		if ($homeaway){
			$rows[] = array($ranking['home_win'],array('class'=>'nw center'));
			if ($competition_sport != 'Beisbol'){
				$rows[] = array($ranking['home_drawn'],array('class'=>'nw center'));
			}
			$rows[] = array($ranking['home_lost'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_favor_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_against_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_diff_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['away_win'],array('class'=>'nw center'));
			if ($competition_sport != 'Beisbol'){
				$rows[] = array($ranking['away_drawn'],array('class'=>'nw center'));
			}
			$rows[] = array($ranking['away_lost'],array('class'=>'nw center'));
			$rows[] = array($ranking['away_favor_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['away_against_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['away_diff_goals'],array('class'=>'nw center'));
		}else{
			$rows[] = array($ranking['home_win'] + $ranking['away_win'],array('class'=>'nw center'));
			if ($competition_sport != 'Beisbol'){
				$rows[] = array($ranking['home_drawn'] + $ranking['away_drawn'],array('class'=>'nw center'));
			}
			$rows[] = array($ranking['home_lost'] + $ranking['away_lost'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_favor_goals'] + $ranking['away_favor_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_against_goals'] + $ranking['away_against_goals'],array('class'=>'nw center'));
			$rows[] = array($ranking['home_diff_goals'] + $ranking['away_diff_goals'],array('class'=>'nw center'));
		}
		$rows[] = array($ranking['diff_goals'], array('class'=>'nw center'));

		if ($competition_sport == 'Beisbol'){
			$rows[] = array($ranking['points']/3, array('class'=>'nw center'));
			$rows[] = array(
				number_format((float) round(($ranking['home_win']+$ranking['away_win']) / $ranking['played'], 3)
					, 3, '.', ''
				)
				, array('class'=>'nw center')
			);
		}else{
			$rows[] = array($ranking['points'], array('class'=>'nw center'));
		}	
	
		echo $this->Html->tableCells(array($rows), array('class'=>'row-a'), array('class'=>'row-b'));
		
		$i++; //Inc the counter
	}
	echo "</table>";

	}

	echo $this->Html->link(
		$this->Html->image("/img/dummies.png", array('class'=>'borderless')),
    	"/Bets/rules", array('escape' => false)
	);

?>

</div>

