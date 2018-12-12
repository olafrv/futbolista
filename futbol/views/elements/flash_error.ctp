<div class="ui-widget" style="margin-left: 40px; margin-right: 40px;">
  <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
     <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <strong><?php if (isset($this->Time)) echo '[' . $this->Time->niceShort() . ']';?>
			<?php echo __('Error', true); ?>:</strong> <?php echo $message ?>
	  </p>
   </div>
</div>  
<br>  
