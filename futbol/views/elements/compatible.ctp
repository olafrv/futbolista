<?php
   echo $this->Html->link(
      $this->Html->image('/img/24_best_edit.png', array('class'=>'borderless', 'align'=>'middle')),
      'http://www.mozilla.com/?from=sfx&amp;uid=0&amp;t=611',
      array('escape' => false)
   );
   echo $this->Html->link(
      $this->Html->image('/img/bb24.jpg', array('class'=>'borderless', 'align'=>'middle')),
      'http://www.blackberry.com',
      array('escape' => false)
   );
   echo $this->Html->link(
      $this->Html->image('/img/chrome_logo_small.png', array('class'=>'borderless', 'align'=>'middle')),
      'http://www.google.com/chrome?hl=es',
      array('escape' => false)
   );

?>
