<div class="container">

	<div class="row" align="center">
		<div class="col-xs-12">
			<?php
				echo $this->cell('Competitions::dropdown', [
					'competition', $competition, '/Bets/index'
				]);
			?>
		</div>	
		<div class="col-xs-12">
			<?php
				 echo $this->cell('Matches::kickoffs', [
					'kickoff', $this->request->session()->read("Match.kickoff"), $matches
				]);
			?>	
		</div>
	</div>
	<br>
		
	<div class="list-group">
	<?php 
	
		$i = 0;

		foreach ($matches as $match):
			
			$i++; // HTML elements ID

			$points = !empty($match->bets) ? $match->bets[0]->points : "";

			$host_symbol = "";
			if (isset($match->host->team))
			{
				if ($match->host->team->has_shield)
				{
					$host_symbol = $this->Html->image(
						'/img/shields/' . $match->host->team->abreviation . '.png'
						, [ 'alt' => $match->host->team->title ]
					);
				}
				else
				{
					$host_symbol = $this->Html->image(
						'/img/flags/' . $match->host->team->country->code . '.gif'
						, [ 'alt' => $match->host->team->title ]
					);
				}
			}

			$guest_symbol = "";
			if (isset($match->guest->team))
			{
				if ($match->guest->team->has_shield)
				{
					$guest_symbol = $this->Html->image(
						'/img/shields/' . $match->guest->team->abreviation . '.png'
						, [ 'alt' => $match->guest->team->title ]
					);
				}
				else
				{
					$guest_symbol = $this->Html->image(
						'/img/flags/' . $match->guest->team->country->code . '.gif'
						, [ 'alt' => $match->guest->team->title ]
					);
				}
			}

			$match_closed = !is_null($match->host_goals) && !is_null($match->guest_goals);

	?>
			<a href="#" class="list-group-item">

				<h4 class="list-group-item-heading">

					<div class="row">	  	

						<div class="col-xs-6">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">
						      <?= $host_symbol; ?>
						      <?= isset($match->host->team) ? $match->host->team->abreviation : '[' . $match->host->abreviation . ']'; ?>
						    </span>
  	  					<?php	if (!$match_closed){ ?>
								<input type="number" class="form-control" id="host_goals_<?= $i; ?>" 
									value="<?= isset($match->bets[0]) ? $match->bets[0]->host_goals : ""; ?>">
								<?php }else{ ?>
								<span class="input-group-btn input-group-sm">
									<button class="form-control <?= $points>0 ? "btn-success" : "btn-danger" ?>">
										<?= $match->host_goals; ?>
								 	</button>
								</span>
								<?php } ?>
							</div>
						</div>

						<div class="col-xs-6">
							<div class="input-group input-group-sm">
  	  					<?php	if (!$match_closed){ ?>
								<input type="number" class="form-control" id="guest_goals_<?= $i; ?>" 
									value="<?= isset($match->bets[0]) ? $match->bets[0]->guest_goals :""; ?>">
								<?php }else{ ?>
								<span class="input-group-btn input-group-sm">
									<button class="form-control <?= $points>0 ? "btn-success" : "btn-danger" ?>">
										<?= $match->guest_goals; ?>
								 	</button>
								</span>
								<?php } ?>
								<span class="input-group-addon">
						      <?= isset($match->guest->team) ? $match->guest->team->abreviation : '[' . $match->guest->abreviation . ']'; ?>
						      <?= $guest_symbol; ?>
						    </span>
							</div>
						</div>

					</div>

				</h4>
		
				<!-- MATCH & BET DETAILS -->
				<p class="list-group-item-text">

				 	<div class="row">
					 	<div class="col-xs-12">		  
				 			<b><?= __('Fase'); ?> / <?= __('Group'); ?>:</b> 	  
							<?= $match->_matchingData["Fases"]->title; ?> 
							<?= $match->_matchingData["Grouppings"]->is_elimination ? ' / ' . $match->_matchingData["Grouppings"]->title : ''; ?>
						</div>
					</div>
					
				 	<div class="row">
					 	<div class="col-xs-12">
						 	<b>Match Kick-Off:</b>
							<?= $match->kickoff; ?>
						</div>
					</div>
				 	<?php if ($match_closed){ ?>			 	
				 	<div class="row">
					 	<div class="col-xs-12">
							<b>Your bet:</b>
							<?= $match->bets[0]->host_goals . ' - ' . $match->bets[0]->guest_goals; ?>
							<i><?= $match_closed ? ("(" . (($points>0 ? '+' : '') . $points) . " pts)") : ''; ?></i>
						</div>
					</div>
					<?php } ?>

					<!-- CHANGE ALERT LABELS-->				
					<script language="javascript">
						$("#host_goals_<?= $i ?>").change(function(event){
							$("#alert_save_ok_<?= $i; ?>").hide();
							$("#alert_save_err_<?= $i; ?>").hide();
							$("#alert_save_pen_<?= $i; ?>").show();
							$("#save_<?= $i; ?>").show();
						});
						$("#guest_goals_<?= $i ?>").change(function(event){
							$("#alert_save_ok_<?= $i; ?>").hide();
							$("#alert_save_err_<?= $i; ?>").hide();
							$("#alert_save_pen_<?= $i; ?>").show();
							$("#save_<?= $i; ?>").show();
						});
					</script>

					<!-- SAVE ALERT LABELS -->
					<div id="alert_save_ok_<?= $i; ?>" style="display:none;">
						<h4>
							<span class="label label-success">
								<span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
								<span id="alert_save_ok_text_<?= $i; ?>">Saved</span>
							</span>
						</h4>
					</div>

					<div id="alert_save_err_<?= $i; ?>" style="display:none;">
						<h4>
							<span class="label label-danger">
								<span class="glyphicon glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
								<span id="alert_save_err_text_<?= $i; ?>">Error</span>	
							</span>
						</h4>
					</div>

					<div id="alert_save_pen_<?= $i; ?>" style="display:none;">
						<h4>
							<span class="label label-warning">
								<span class="glyphicon glyphicon glyphicon glyphicon-alert" aria-hidden="true"></span>
								<span id="alert_save_pen_text_<?= $i; ?>">Please save modifications</span>
							</span>
						</h4>
					</div>

					<!-- SAVE BUTTON -->
					<?php if (!$match_closed){ ?>
					<div align="right">
						<button type="button" class="btn btn-sm btn-primary" id="save_<?= $i ?>" style="display: none;">
							Save
						</button>
					</div>
					<script language="javascript">
						$("#save_<?= $i ?>").click(function (){
							$.post(
								"<?= $this->Url->build('/Bets/save') ?>"
								, { 
										host_goals: $("#host_goals_<?= $i ?>").prop('value')
										, guest_goals: $("#guest_goals_<?= $i ?>").prop('value') 
										, match_id: <?= $match->id; ?>
									}
								, function (json) {
										$.each(json, function(index, object){
											if (undefined === object.error){
												$("#alert_save_pen_<?= $i ?>").hide();
												$("#alert_save_err_<?= $i ?>").hide();
												$("#alert_save_ok_<?= $i ?>").show();
												$("#save_<?= $i; ?>").hide();
											}else{
												$("#alert_save_err_text_<?= $i ?>").text('Error: ' + object.error);									
												$("#alert_save_err_<?= $i ?>").show();											
												$("#save_<?= $i; ?>").show();
											}
										});
								}
								,"json"
							).fail( function(data) {
								$("#alert_save_err_text_<?= $i ?>").text('Unknown error');
								$("#alert_save_err_<?= $i ?>").show();
								$("#save_<?= $i; ?>").show();
	 					  });
						});
					</script>
					<?php } ?>
				</p>
			</a>
	<?php endforeach; ?>
	</div>

</div> 

