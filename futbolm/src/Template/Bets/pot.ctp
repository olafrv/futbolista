<?php // ?>
<div class="container">

	<div class="row" align="center">
		<div class="col-xs-12">
			<?php
				echo $this->cell('Competitions::dropdown', [
					'competition', $competition, '/Bets/pot'
				]);
			?>
		</div>	
	</div>
	<br>
	<?php if (!empty($pot) && !empty($pot[0])): ?>
	<table class="table table-striped table-condensed" style="width:20%;">
		<tr>
			 <th><?php echo __('Position', true); ?></th>
		</tr>
		<?php
			 $i = 0;
			 foreach ($pot as $amount):
				 $i++;
		?>
		<tr>
			 <td>
				<strong>
				<?php
					if ($i==1){
						echo $this->Html->image('/img/medal_gold.png');
					}else if ($i==2){
						echo $this->Html->image('/img/medal_silver.png');
					}else if ($i==3){
						echo $this->Html->image('/img/medal_bronze.png');
					}
					echo $i . ": " . $amount; 
				?>
				</strong>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
</div>

