<?php

   echo $this->Html->link(
      $this->Html->image('/img/le-logo-standard.png', array('class'=>'borderless', 'align'=>'middle', 'height'=>'100px')),
      'https://letsencrypt.org',
      array('escape' => false,'target'=>'_blank')
   );

