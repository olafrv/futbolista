<?php
   echo '<h1>'.__('Posiciones de Usuarios',true).'</h1>';

   echo $this->element("my-menu");

   echo $this->FutbolGui->makeCompetitionForm($competition_list, $competition_id, 'Competition', 'Bets', 'top10');
   echo '&nbsp;&nbsp;';
   echo $this->Html->image('/img/preppy.png', array('border'=>0, 'class'=>'borderless'));
   echo '<b>' . __('Mostrar todos los usuarios', true) . '</b>&nbsp;';
   echo $this->Form->checkbox('showall',
      array(
         'value' => '1',
         'onchange' => "javascript:f=Jolaf.gebi('CompetitionForm'); f.submit();"
      )
   );
/*
	 echo "&nbsp;";
	 echo $this->Form->button('Actualizar',
      array(
         'type' => 'button',
         'onclick' => "javascript:disabled=true; f=Jolaf.gebi('CompetitionForm'); f.action='top10'; f.submit();"
      )
   );
*/
   echo $this->Form->end();

if (count($points)){

?>

<div align="center">
<table width="90%">
<tr>
   <th><?php echo __('Posición', true); ?></th>
   <th><?php echo __('Usuarios', true); ?></th>
   <th><?php echo __('Nº de Usuarios', true); ?></th>
   <th><?php echo __('Puntos', true); ?></th>
   <th><?php echo __('Diferencia de Puntos (Pos.+1)', true); ?></th>
   <th><?php echo __('Diferencia de Puntos (Nro.1)', true); ?></th>
</tr>
<?php
   $style = 'row-a';
   $i = 0;
   $points_g = array();
   $users_g = 0;
   $last_point = null;
	 $first_point_pos = 0;
   foreach ($points as $point):
      //$actual_point = $point['Point']['points'];
      $actual_point = $point[0]['spoints']; // Multi-Competencia (Pote Unico)
			if (is_null($last_point)) $first_pos_point = $actual_point;
      $actual_username = $point['User']['username'];
      $actual_user_id = $point['User']['id'];
      $actual_link = $this->Html->link($actual_username, '/bets/mine/user:' . $actual_user_id . '/competition:' . $competition_id);
      if (!isset($points_g[$actual_point])){
          $points_g[$actual_point] = array($actual_link,0,null);
      }else{
          $points_g[$actual_point][0] .= ", " . $actual_link;
      }
		if (is_null($last_point)) $last_point = $actual_point;
      $points_g[$actual_point][1]++;
		if (is_null($points_g[$actual_point][2])) $points_g[$actual_point][2] = $actual_point - $last_point; //Point diff from before pos
		$last_point = $actual_point;
   endforeach;

   foreach ($points_g as $point => $groupped):
?>
<tr class="<?php echo $style; ?>">
   <td class="center"><?php echo ++$i; ?></td>
   <td class="center" width="300px">
		<?php
			if ($i==1){
				echo $this->Html->image('/img/medal_gold.png', array('class'=>'borderless'));
			}else if ($i==2){
				echo $this->Html->image('/img/medal_silver.png', array('class'=>'borderless'));
			}else if ($i==3){
				echo $this->Html->image('/img/medal_bronze.png', array('class'=>'borderless'));
			}
			echo $groupped[0]; 
		?>
	</td>
   <td class="center"><?php echo $groupped[1]; ?></td>
   <td class="center"><?php echo $point; ?></td>
   <td class="center"><?php echo $groupped[2]; ?></td>
   <td class="center"><?php echo - $first_pos_point + $point; ?></td>
</tr>
<?php
      $style = $style=='row-a'?'row-b':'row-a';
   endforeach;
?>
</table>

<?php
  $free_users_number = count($free_users);
	if ($free_users_number>0){
		echo "<b>Inactivos:</b> ";
		echo array_shift($free_users);
		while(count($free_users)>0){
			echo ", " . array_shift($free_users);
		}
		echo ".";
	}
	echo "<br><br>";
?>
</div>
<p align="left">
<b>Notas:</b>	
      <ul>
				<li>La tabla muestra los usuarios ordenados por los puntos acumulados. <br/>
  	    <li>Los puntos se calculan cada vez que se actualiza el resultado de un partido.
				<li>El cálculo solo incluye a usuarios que han introducido al menos una predicción.
			</ul>
</p>
<?php
}
?>
