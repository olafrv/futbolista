<?php

	foreach($fixtures as  $fixture){
		$kickoff = $this->Time->niceShort($fixture['Match']['kickoff']);
		echo $this->Html->button('['.$kickoff.']', $url.'/fixture:'.$kickoff);
	}
