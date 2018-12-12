<div class="btn-group btn-group-sm">
	<?php
		foreach($competitions as $competition){
			$text = ($selected_value == $competition->id) ? $competition->title : "";
			if (!empty($text)) break;
		}
	?>
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?= $text ?> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
	<?php
		foreach($competitions as $competition){
			echo '<li><a href="' . $this->Url->build($action . '/' . $competition->id) . '">'. $competition->title . '</a></li>';
		}
	?>
  </ul>
</div>

