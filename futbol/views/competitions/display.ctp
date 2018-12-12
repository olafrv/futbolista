<?php	echo $this->element('mobile'); ?>

<?php echo '<h1>'.__('Bienvenido', true).'</h1>'; ?>

<!-- Facebook - Like button 
<div id="fb-root"></div><script src="https://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="https://www.olafrv.org/futbol" width="292" show_faces="false" border_color="" stream="false" header="false"></fb:like-box>
-->

<p>
Contribuye con nuestra comunidad mientras disfrutas de la emoción de predecir el resultado de los partidos de futbol internacional
<?php echo $this->Html->link('registrate y disfruta', '/users/register', array('style'=>'font-weight: bold;'));?> de la diversión.
</p>

<?php if (count($photos)>0): ?>
<br>
<div id="sliderFrame">
    <div id="slider">
<!--
        <a href="http://www.menucool.com/javascript-image-slider" target="_blank">
            <img src="images/image-slider-1.jpg" alt="Welcome to Menucool.com" />
        </a>
        <img src="images/image-slider-3.jpg" alt="Pure Javascript. No jQuery. No flash." />
        <img src="images/image-slider-4.jpg" alt="#htmlcaption" />
-->
<?php
	foreach ($photos as $photo){
		$photo = $photo["Photo"];
		echo $this->Html->image("/img/photos/". $photo["name"], array(
				//'width'=>'200px',
				'url'=>Configure::read("Futbol.serverWwwSsl") . "/img/photos/". $photo["name"],
				'alt'=>$photo["title"]
			)
		);
	}
?>
    </div>
    <div id="htmlcaption" style="display: none;">
        <em>HTML</em> caption. Link to <a href="http://www.google.com/">Google</a>.
    </div>
</div>
<?php endif; ?>
