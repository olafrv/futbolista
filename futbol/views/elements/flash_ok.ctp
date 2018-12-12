<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin-left: auto; margin-right: auto; padding: 0 .7em;"> 
		<p>
			<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			<strong><?php if (isset($this->Time)) echo '[' . $this->Time->niceShort() . '] '; ?></strong>
			<?php echo $message; ?>
		</p>
	</div>
</div>
<br>
