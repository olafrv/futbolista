<?php


  echo "<h1>Mis predicciones</h1>";

	echo $this->element("my-menu");

	$i = 0;

	if ($betable && !$other_user){
		$header = "Después de colocar su predicciones haga clic en el botón <strong>GUARDAR</strong><br><br>";
	}else{
		$header="";
	}

	if ($User['username']!='admin' && $User["mailing_list"]==0){
?>
   <p>
		  Parece que <b>no está suscrito a nuestro grupo de correo electrónico</b>
   	  visite la sección <?php echo $this->Html->link('Contáctenos','/bets/contact'); ?>
      y siga las instrucciones para incluirse en la lista de distribución de correos 
      electrónicos de noticias.
	</p>
<?php
	}
?>
<div align="center">

<div id="teamdialog" title="<?php __("Equipo"); ?>"></div>
<script language="Javascript">
	function showTeam(id){
		url="<?php echo Configure::read('Futbol.serverSslUrl')?>/Teams/show/id:"+id;
		$("#teamdialog").load(url).dialog({modal:true}); 
	}
</script>

<div style="text-align: center;">
<?php
	
	echo $other_user ? 'El usuario '. $User['username'] : 'Ud.';
	echo ' tiene <b style="color: #2180BC;">' . $bet_total_points.' puntos</b> acumulados en esta competencia.';

  if (!$betable && !$other_user && $User['username']!='admin'){
		echo '<br/>';
    echo '<font style="font-weight: bold; color: red;">';
		echo 'Para pagar por nuestros servicios visite la sección ' . $this->Html->link('Pagos','/payments/show'); 
		echo '.</font>';
	}
?>
</div>

<?php
	$action = !$other_user ? 'mine' : 'mine/user:' . $User['id'];

	echo $this->FutbolGui->makeFixtureForm($competition_list, $competition_id, 
		$fase_list, $fase_id, $groupping_list, $groupping_id, true, $fixture_list, $fixture, true, 'Bet', $action, $header);

   if ($User['username']!='admin' && !$other_user){
		echo '&nbsp;&nbsp;';
		echo $this->Form->button('Guardar', 
			array(
				'type' => 'button', 'class'=>'button-blue',
				'onclick' => 
					"javascript:disabled=true; f=Jolaf.gebi('BetForm'); f.action+='/save_button:1'; f.submit();"
			)
		);
	}
	if ($competition_sport == 'Futbol'){
		$left_role  = 'Anfitrión';
		$right_role = 'Visitante';
	}else{
		$right_role = 'Anfitrión';
		$left_role  = 'Visitante';
	}
?>
	<table>
		<tr>
			<th class="nw">#<sup>(id)</sup></th>
			<th><?php echo __($left_role, true); ?></th>
			<th><?php echo __('Resultado', true); ?></th>
			<th><?php echo __($right_role, true); ?></th>
			<th><?php echo __('Fecha', true); ?></th>
			<th><?php echo __('Predicción', true); ?></th>
			<th><?php echo __('Puntos', true); ?></th>
			<th><?php echo __('Total', true); ?></th>
		</tr>
<?php
		foreach ($bet_table as $row) {

		$match_id = $row["Match"]["id"];
		$match_id_hidden = "";

		$is_pending = isset($row["Match"]["is_pending"]) ? $row["Match"]["is_pending"] : false;	

		//$host = isset($row["PseudoTeamHost"]["Team"]["abreviation"]) ?
		//	$row["PseudoTeamHost"]["Team"]["abreviation"] : $row["PseudoTeamHost"]["abreviation"];
		$host = isset($row["PseudoTeamHost"]["Team"]["title"]) ?
			$row["PseudoTeamHost"]["Team"]["title"] : $row["PseudoTeamHost"]["abreviation"];

		if (isset($row["PseudoTeamHost"]["Team"]["id"])){
			$host = "<a href=\"javascript:showTeam('".$row["PseudoTeamHost"]["Team"]["id"]."');\">$host</a>";
		}

		$host_flag = isset($row["PseudoTeamHost"]["Team"]["Country"]["code"]) ? 
			$row["PseudoTeamHost"]["Team"]["Country"]["code"] : "";

		$host_shield = "";
		if (isset($row["PseudoTeamHost"]["Team"]["has_shield"])){
			$host_shield = $row["PseudoTeamHost"]["Team"]["has_shield"]==1 ? 
				$row["PseudoTeamHost"]["Team"]["abreviation"] : "";
		}

		$host_flag = $host_flag != "" ? $this->Html->image('/img/flags/' . 
			$host_flag . '.gif', array('border'=>0, 'class'=>'borderless')) : "";

		$host_shield = $host_shield != "" ? $this->Html->image('/img/shields/' . 
			$host_shield . '.png', array('border'=>0, 'class'=>'borderless')) : "";

		$host_symbol = $host_shield != "" ? $host_shield : $host_flag;

		//$guest = isset($row["PseudoTeamGuest"]["Team"]["abreviation"]) ?
		//	$row["PseudoTeamGuest"]["Team"]["abreviation"] : $row["PseudoTeamGuest"]["abreviation"];
		$guest = isset($row["PseudoTeamGuest"]["Team"]["title"]) ?
			$row["PseudoTeamGuest"]["Team"]["title"] : $row["PseudoTeamGuest"]["abreviation"];

		if (isset($row["PseudoTeamGuest"]["Team"]["id"])){
			$guest = "<a href=\"javascript:showTeam('".$row["PseudoTeamGuest"]["Team"]["id"]."');\">$guest</a>";
		}
				
		$guest_flag = isset($row["PseudoTeamGuest"]["Team"]["Country"]["code"]) ?
			$row["PseudoTeamGuest"]["Team"]["Country"]["code"] : "";

		$guest_shield = "";
		if (isset($row["PseudoTeamGuest"]["Team"]["has_shield"])){
			$guest_shield = $row["PseudoTeamGuest"]["Team"]["has_shield"]==1 ? 
				$row["PseudoTeamGuest"]["Team"]["abreviation"] : "";
		}
		
		$guest_flag = $guest_flag != "" ? $this->Html->image('/img/flags/' . 
			$guest_flag . '.gif', array('border'=>0, 'class'=>'borderless')) : "";

		$guest_shield = $guest_shield != "" ? $this->Html->image('/img/shields/' . 
			$guest_shield . '.png', array('border'=>0, 'class'=>'borderless')) : "";
		
		$guest_symbol = $guest_shield != "" ? $guest_shield : $guest_flag;

		$host_goals = isset($row["Match"]["host_goals"]) ? $row["Match"]["host_goals"] : "";	 

		$guest_goals = isset($row["Match"]["guest_goals"]) ? $row["Match"]["guest_goals"] : "";	 

		$bet_id = isset($row["Bet"][0]["id"]) ? $row["Bet"][0]["id"] : "";	 
		$bet_id_hidden = "";
	
		$bet_host_goals = isset($row["Bet"][0]["host_goals"]) ? $row["Bet"][0]["host_goals"] : "";	 
		$bet_guest_goals = isset($row["Bet"][0]["guest_goals"]) ? $row["Bet"][0]["guest_goals"] : "";	 

/*
		if ($row['Match']['has_penalties']){
			$bet_host_penalties = isset($row["Bet"][0]["host_penalties"]) ? $row["Bet"][0]["host_penalties"] : "";	 
			$bet_guest_penalties = isset($row["Bet"][0]["guest_penalties"]) ? $row["Bet"][0]["guest_penalties"] : "";	 

			$bet_penalties_show = 
				(($bet_host_penalties!=null && $bet_guest_penalties!=null 
					&& $bet_host_penalties!="" && $bet_guest_penalties!="") or 
						$bet_host_goals==$bet_guest_goals) ? "block" : "none";
		}

		$bet_penalties = "";
*/

		if ($User['username']!='admin' && !$other_user && $is_pending){
			
			$match_id_hidden = $this->Form->hidden("Bet.$i.match_id", array('value'=>$match_id));

			$bet_id_hidden = $this->Form->hidden("Bet.".$i.".id", array('value'=>$bet_id));

			$bet_host_goals_options = array(
				'class'=>'futbol_goals' 
				,'maxlength'=>2
				,'value'=>$bet_host_goals
				,'id'=>'BetHostGoalsInput'.$row['Match']['id']
			);
/*			
			if ($row['Match']['has_penalties']){
				$bet_host_goals_options['onkeyup'] =
					'if (Jolaf.gebi("BetHostGoalsInput'.$row['Match']['id'].'").value==Jolaf.gebi("BetGuestGoalsInput'.$row['Match']['id'].'").value){
					    Jolaf.gebi("BetPenaltiesDiv'.$row['Match']['id'].'").style.display="block";
                }else{
						 Jolaf.gebi("BetHostPenaltiesInput'.$row['Match']['id'].'").value="";
						 Jolaf.gebi("BetGuestPenaltiesInput'.$row['Match']['id'].'").value="";
						 Jolaf.gebi("BetPenaltiesDiv'.$row['Match']['id'].'").style.display="none";
					 }';
			}
*/
		  $bet_host_goals = $this->Form->text("Bet.$i.host_goals", $bet_host_goals_options);

			$bet_guest_goals_options = array(
				'class'=>'futbol_goals'
				, 'maxlength'=>2
				, 'value'=>$bet_guest_goals
				, 'id'=>'BetGuestGoalsInput'.$row['Match']['id']
			);
/*
			if ($row['Match']['has_penalties']){
				$bet_guest_goals_options['onkeyup'] =
					'if (Jolaf.gebi("BetHostGoalsInput'.$row['Match']['id'].'").value==Jolaf.gebi("BetGuestGoalsInput'.$row['Match']['id'].'").value){
					    Jolaf.gebi("BetPenaltiesDiv'.$row['Match']['id'].'").style.display="block";
                }else{
						 Jolaf.gebi("BetHostPenaltiesInput'.$row['Match']['id'].'").value="";
						 Jolaf.gebi("BetGuestPenaltiesInput'.$row['Match']['id'].'").value="";
						 Jolaf.gebi("BetPenaltiesDiv'.$row['Match']['id'].'").style.display="none";
					 }';
			}
*/
	   	$bet_guest_goals = $this->Form->text("Bet.$i.guest_goals", $bet_guest_goals_options);
/*
			if ($row['Match']['has_penalties']){

				$bet_host_penalties = $this->Form->text("Bet.$i.host_penalties", array('class'=>'futbol_penalties', 'maxlength'=>2,
					'value'=>$bet_host_penalties
					,'id'=>'BetHostPenaltiesInput'.$row['Match']['id']
				));

				$bet_guest_penalties = $this->Form->text("Bet.$i.guest_penalties", array('class'=>'futbol_penalties', 'maxlength'=>2,
					'value'=>$bet_guest_penalties
					,'id'=>'BetGuestPenaltiesInput'.$row['Match']['id']
				));
				
				$bet_penalties .= '<div style="display: '.$bet_penalties_show.';" id="BetPenaltiesDiv'.$row['Match']['id'].'">' . 
					__('Penales',true) . ": $bet_host_penalties - $bet_guest_penalties" . "</div>";
			}
*/
		}

		if ($competition_sport == "Futbol"){
			$bet_goals = $bet_host_goals . '-' . $bet_guest_goals;
			$score = $host_goals .'-'. $guest_goals;
			$left_symbol = $host_symbol . ' ' . $host;
			$right_symbol = $guest_symbol . ' ' . $guest;
		}else{
			$bet_goals = $bet_guest_goals . '-' . $bet_host_goals;
			$score = $guest_goals .'-'. $host_goals;
			$left_symbol = $guest_symbol . ' ' . $guest;
			$right_symbol = $host_symbol . ' ' . $host;
		}
/*
		if ($row['Match']['has_penalties']){
			$bet_goals .= $bet_penalties!="" ? $bet_penalties : "<br/>".__('Penales',true).": " . $bet_host_penalties . " - " . $bet_guest_penalties;
		}
*/
		$kickoff = $this->Time->niceShort($row["Match"]["kickoff"]);

		$ponderation = $row["Match"]["ponderation"];

		$points = isset($row["Bet"][0]["points"]) ? $row["Bet"][0]["points"] : 0;	

		if ($User['username']=='admin'){
			$scoreLink = "<b>" . $this->Html->link(
				strlen($score)==1?__('Cargar',true):$score
					, '/Matches/index/todo:edit/id:'.$match_id
			) . "</b>";
		}else{
			$scoreLink = "<b style='color: #2180BC;'>$score</b>";
		}

		$cells =	
			array(
				array(
					array(
						($i+1 
							. '<sup>('.$match_id.')</sup>'  
							. $match_id_hidden)
							, array('class'=>'nw'
						)
					),
					array($left_symbol, array('class'=>'nw l')),
					array($scoreLink, array('class'=>'nw center')),
					array($right_symbol, array('class'=>'nw l')),
					array($kickoff, array('class'=>'nw center')),
					array(
							(
							"<b>"
							. ($points>0 ?	"<font color='green'>$bet_goals</font>" : 
									(strlen($score)==1 ? "<font color='black'>$bet_goals</font>" : "<font color='red'>$bet_goals</font>")
								)
							. $bet_id_hidden
							. "</b>"
							) , array('class'=>'nw center')
					) , array(
						$ponderation == 1 ? round($points/$ponderation,2) :  round($points/$ponderation,2) . 'x' . $ponderation
						, array('class'=>'nw center')
					),
					//array($ponderation . ' x ' . round($points/$ponderation,2), array('class'=>'nw center')),
					//array('x' . $ponderation, array('class'=>'nw center')),
					array(round($points,2), array('class'=>'nw center'))
				)
		);
		
		echo $this->Html->tableCells($cells, array('class'=>'row-a'), array('class'=>'row-b'));
		
		$i++; //Inc the counter
	}
	echo "</table>";
	echo $this->Form->end(NULL);
/*
   echo $this->Html->link(
      $this->Html->image("/img/dummies.png", array('class'=>'borderless')),
      "/Bets/rules", array('escape' => false)
   );
*/

?>
</div>
