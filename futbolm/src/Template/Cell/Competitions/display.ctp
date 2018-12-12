<!-- src/Template/Cell/Competitions/display.ctp -->
<select id="<?= $id ?>" class="form-control">
<?php

foreach($competitions as $competition){
	$selected = (($selected_value == $competition->id) ? "selected" : "");
	echo '<option value="' . $competition->id . '" ' . $selected . '>' . $competition->title . '</option>';
}

?>
</select>

