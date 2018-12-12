<?php
  echo "<p style='font-weight: bold; text-align: center;'>";
  echo $this->Html->image("survey-mini.png", array('class'=>'borderless'));
  echo $this->Html->link('[Mis Predicciones]','/bets/mine');
  echo "&nbsp;&nbsp;";
  echo $this->Html->image("soccer-referee-grass-icon_mini.png", array('class'=>'borderless'));
  echo $this->Html->link('[Todas las Predicciones]','/bets/forecast');
  echo "&nbsp;&nbsp;";
  echo $this->Html->image("Actions-bookmark-new-list-icon_mini.png", array('class'=>'borderless'));
  echo $this->Html->link('[Posiciones]','/bets/top10');            
  echo "&nbsp;&nbsp;";
  echo $this->Html->image("Generate-tables-icon_mini.png", array('class'=>'borderless'));
  echo $this->Html->link('[Clasificación]','/rankings/show');
  echo "</p>";

