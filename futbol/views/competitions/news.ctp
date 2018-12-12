<h1><?php echo __('Noticias', true); ?></h1>
<br>
<!-- INICIO - RSS -->
<?php if (!is_null($rss_url) && $rss_url!=""){ ?>
  <h2><?php echo $competition_title; ?></h2>
  <ul>
  <?php
		echo "<table>";
		$i = 0;
  	foreach ($competition_rss as $item){
			$title = html_entity_decode($item["title"], ENT_QUOTES, "utf-8");
			$url = $item["link"];
			$date = $this->Time->timeAgoInWords(date('Y-m-d H:i:s', $item["date"]));
			if (!empty($item["enclosure"]) && strpos($item["enclosure"]["type"],'image')!==false){
				$thumbnail = $this->Html->image(
					$item['enclosure']['link']
					, array('height'=>'100px')
				);
			}else{
				$thumbnail = false;
			}
			echo "<tr class='".($i++%2==0?'row-a':'row-b')."'>";
			if ($thumbnail) echo "<td>$thumbnail</td>";
			echo "<td colspan='".($thumbnail?2:0)."'>";
			echo "$date - <strong>" . $this->Html->link($title, $url, array('target'=>'_blank')) . '</strong>';
			echo "</td>";
			echo "</tr>";
			if ($i==10) break;
		}
		echo "</table>";
  ?>
  </ul>
  <p><?php echo __('Fuente RSS', true) . ': <a href=\'' .  $rss_url . '\'>' .  $rss_url . '</a>'; ?> </p>
<?php }else{ ?>
  <p><?php echo __('No hay una fuente RSS de noticias registrada para esta competencia.', true); ?>
<?php } ?>
<!-- FIN - RSS -->
