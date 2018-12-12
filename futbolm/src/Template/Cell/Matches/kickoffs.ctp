<?php

	$today = date('Y-m-d');
	if (empty($selected_value)){
		$is_today = array_search($today, $kickoffs);
		if ($is_today){
			$selected_value = $today;
		}else{
			$selected_value = $kickoffs[0];
		}
	}
	
	$text = NULL;
	foreach($kickoffs as $kickoff){
		$text = ($selected_value == $kickoff) ? $kickoff : NULL;
		if (!empty($text)){
			break;
		}
	}
	if ($today==$text) $text = __('Today');
	
	$today_key = array_search($selected_value, $kickoffs);
?>

<div class="btn-group btn-group-sm">

  <!-- Matches count -->
	<div class="btn-group btn-group-sm">
		<button class="btn btn-primary" type="button">
			<?= __('Matches'); ?>&nbsp;&nbsp;
			<span class="badge"><?=count($matches);?></span>
		</button>
	</div>
	
	<!-- Kick-Off list -->
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?= $text ?> <span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
	<?php
		foreach($kickoffs as $row){
			$kickoff = date('Y-m-d', strtotime($row)); 
			echo '<li>';
			echo '<a href="' . $this->Url->build('/Bets/index/' 
				. $this->request->session()->read("Competition.id")
					. '/' . $kickoff) . '">'. $kickoff . '</a>';
			echo '</li>';
		}
	?>
	</ul>

	<!-- Previous button -->
	<?php 
		if ($today_key!==FALSE && $today_key > 0){ 
			$kickoff = $kickoffs[$today_key-1];
			$url = $this->Url->build('/Bets/index/' 
				. $this->request->session()->read("Competition.id")
					. '/' . $kickoff);

	?>
  <a class="btn btn-default" href="<?= $url; ?>">
   	<span class="glyphicon glyphicon-chevron-left"></span>
   	<?= __('Previous'); ?>
  </a>
  <?php } ?>

	<!-- Next button -->
	<?php 
		if ($today_key!==FALSE && $today_key < count($kickoffs)-1){ 
			$kickoff = $kickoffs[$today_key+1];
			$url = $this->Url->build('/Bets/index/' 
				. $this->request->session()->read("Competition.id")
					. '/' . $kickoff);

	?>
  <a class="btn btn-default" href="<?= $url; ?>">
	  <?= __('Next'); ?>
  	<span class="glyphicon glyphicon-chevron-right"></span>
  </a>
  <?php } ?>
	
</div>    


