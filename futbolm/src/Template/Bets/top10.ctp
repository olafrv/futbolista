<?php // ?>
<div class="container">

	<div class="row" align="center">
		<div class="col-xs-12">
			<?php
				echo $this->cell('Competitions::dropdown', [
					'competition', $competition, '/Bets/top10'
				]);
			?>
		</div>	
	</div>
	<br>

	<table class="table table-striped table-condensed">
		<tr>
			 <th><?php echo __('Position', true); ?></th>
			 <th><?php echo __('User', true); ?></th>
			 <th><?php echo __('Points', true); ?></th>
		</tr>
		<?php
			 $points_g = array();
			 $users_g = 0;
			 $last_point = null;
			 $first_point_pos = 0;
			 foreach ($points as $point):
				  $actual_point = $point[0]['spoints'];
					if (is_null($last_point)) $first_pos_point = $actual_point;
				  $actual_username = $point['User']['username'];
				  $actual_user_id = $point['User']['id'];
				  $actual_link = $this->Html->link($actual_username, '/bets/mine/user:' . $actual_user_id . '/competition:' . $competition);
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

			 $i = 0;
			 foreach ($points_g as $point => $groupped):
		?>
		<tr>
			 <td><?php echo ++$i; ?></td>
			 <td>
				<?php
					if ($i==1){
						echo $this->Html->image('/img/medal_gold.png');
					}else if ($i==2){
						echo $this->Html->image('/img/medal_silver.png');
					}else if ($i==3){
						echo $this->Html->image('/img/medal_bronze.png');
					}
					echo $groupped[0]; 
				?>
			</td>
			<td><?php echo $point; ?></td>
		</tr>
		<?php
			 endforeach;
		?>
	</table>
</div>

