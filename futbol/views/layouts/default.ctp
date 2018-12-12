<?php //Forzar coloreado PHP en el editor ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="EXPIRES" content="<?php echo date('r'); ?>"/>
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
	<meta http-equiv="PRAGMA" content="NO-CACHE"/>
	<meta http-equiv="content-language" content="es">
	<meta name="robots" content="index,follow,noarchive" /> 
	<meta name="keywords" content="futbol,partidos,equipos,grupos,copa,clásico,derby" />
	<meta name="description" content="futbol,partidos,equipos,grupos,copa,clásico,derby" />
	<meta name="version" content="<?php echo Configure::read('Version.previousGitVersion');?>" />  
	<?php echo $html->charset(); ?>
	<!-- BEGIN - Facebook - Open Graph Tag -->
	<meta property="og:title" content="<?php echo Configure::read('Futbol.siteName'); ?>" />
	<meta property="og:type" content="sport" />
	<meta property="og:url" content="<?php echo Configure::read('Futbol.serverSslUrl'); ?>" />
	<meta property="og:image" content="<?php echo Configure::read('Futbol.serverSslUrl') . '/img/balon.jpg';?>" />
	<meta property="og:site_name" content="<?php echo Configure::read('Futbol.siteName'); ?>" />
	<meta property="fb:admins" content="677635869" />
	<!-- END - Facebook - Open Graph Tag -->
	<title><?php echo Configure::read('Futbol.siteName') . ' ::' . $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon', $this->webroot . '/img/balon.icon.png') . "\n";
		echo $this->Html->css('envision1.0.css') . "\n"; //Modified Envision CSS for Futbol
		echo $this->Html->css('futbol.css?' . time()) . "\n"; //Own CSS for Futol
		echo $this->Html->css('cake.futbol.css?' . time()) . "\n"; //Modified Cake CSS for Futbol
		echo $this->Html->script('jolaf') . "\n"; // Include jOlaf library
		// JQuery + JQuery UI - Begin
		//echo $this->Html->script('jquery-1.10.2.min'); // Include jQuery library
		//echo $this->Html->script('jquery-ui-1.10.3.custom.min'); // Include jQuery UI library
		//echo $this->Html->css('/css/start/jquery-ui-1.10.3.custom.min'); // Include jQuery UI library
		echo $this->Html->script('jquery-1.12.1.min') . "\n"; // Include jQuery library
		echo $this->Html->script('jquery-ui-1.11.4/jquery-ui.min') . "\n"; // Include jQuery UI library
		echo $this->Html->css('/js/jquery-ui-1.11.4/jquery-ui.min') . "\n"; // Include jQuery UI library
		// JQuery + JQuery UI - End
		echo $this->Html->script('clock') . "\n"; // Include clock functions
		if ($this->params["controller"]=="competitions" && $this->params["action"]=='display'){
			echo $this->Html->css('/jsImgSlider/themes/1/js-image-slider.css') . "\n";
			echo $this->Html->script('/jsImgSlider/themes/1/js-image-slider.js') . "\n";
		}
		echo $scripts_for_layout; // After all $this->Html->script!
	?>
</head>
<body onload="clock_start();" style="background: #FAFAFA url(<?php echo $this->webroot; ?>/img/grass.jpg) repeat 5px 5px;">
<?php

	echo "<!-- SERVER DATE: " . date("r") . "-->\n";

   // BEGIN - Internet Explorer Warning Message
   // ---- $browser = get_browser(null, true);
   if (false && ($browser['browser']=='IE' || $browser['browser'] == 'MSIE') && ((int) $browser['majorver'])<7):
?>
<div id="futbol_ie_warning" align="center">
   <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home">
   <?php echo $this->Html->image('/img/internet_explorer.gif', array('class'=>'borderless', 'style'=>'width: 35px; height: 35px;')); ?>
   Ud. tiene <b>Internet Explorer <?php echo $browser["majorver"]; ?></b> pero esta página se visualiza mejor en una <b>versión más reciente</b>.
   </a>
</div>
<script language="Javascript">Jolaf.gebi("futbol_ie_warning").style.display = 'block';</script>
<?php    
   endif;
   // END - Internet Explorer Warning Message
?>

<?php if (Configure::read('debug')>0){ ?>
<div id="mantred" style="background-color: #FA5858;">
	<font color="white" size="2" style="font-weight: bold;">
  	Ambiente de Desarrollo
	</font>
</div>
<?php } ?>

<div id="wrap">
  <div id="header">
    <a href="<?php echo Configure::read('Futbol.serverSslUrl'); ?>" id="header-link" style='color: white; text-decoration: none; '></a>
    <div id="header-links">
     <p style="font-size: 12px;" align="right"> 
				<font style='font-weight: bold;'>
      	<?php 
					if ($this->Session->read('Auth.User.username')=="admin"){
						echo $this->Html->link(__('Configuración', true) . ' | ', '/admins');
					}
					if ($this->Session->read('Auth.User')){
						echo $this->Html->link(__('Cambiar Clave', true) . ' | ', '/users/changePassword');
						$user = $this->Session->read('Auth.User');
						echo $this->Html->link(__('Salir', true), '/users/logout', array('class'=>'button-red'));
					}else{
						echo $this->Html->link(__('Entrar al Sistema', true), '/users/login');
						echo $this->Html->link(' | ' . __('¿Olvidó su clave o usuario?', true), '/users/forgot', array('style'=>'color: #CECECE;'));
					}
				?>
				</font>
				<?php if (isset($user)){ ?>
				<font style='font-size: 12px; color: #EFEADC;'>
					<br>
					<?php echo "Bienvenido, <b>***"  .$user["username"] . "***</b>"; ?>
				</font>
				<?php } ?>
			</p>
    </div>
	</div>
	<div id="menut">
		<table align="right">
			<tr>
    <?php
			// Menu ($m_* vars are used here!!!)
			$m_parts = array(
				'Inicio' => array('/Pages/display',$this->webroot . '/img/home.png', false),
				'Prediciones' => array('/Bets/mine',$this->webroot . '/img/checklist.png', true),
				'Mensajes' => array('/Mails/messages',$this->webroot . '/img/mail.png', true),
				'Competencias' => array('/Competitions/info',$this->webroot .  '/img/stadium.png', false),
				'Fotos' => array('/Photos/show',$this->webroot .  '/img/camera-mini.png', true),
				'Noticias' => array('/Competitions/news',$this->webroot . '/img/network.png', true),
				'Pagos' => array('/Payments/show', $this->webroot . '/img/credit_cards.png', false),
				'Reglas' => array('/Bets/rules', $this->webroot . '/img/balon.icon.png', false),
				'Ayuda' => array('/Bets/contact',$this->webroot . '/img/chat.png', false)
			);
			$m_auth  = $this->Session->read('Auth.User') ? 'auth' : 'noauth';
			$m_cur_path = '/'.isset($this->params['controller']) ? '/'.$this->params['controller'] : '';
			$m_cur_path .= isset($this->params['action']) ? '/'.$this->params['action'] : '';
			$m_current[$m_cur_path] = true;
			foreach($m_parts as $m_title => $m_data):
				if ($this->Session->read('Auth.User') || $m_data[2]==false){
					if ($m_data[0]=='/Pages/display'){
						$m_url = '/'; 
					}else{
						$m_url = $m_data[0];
					}
					echo '<td ' 
						. (isset($m_current[$m_data[0]]) ? 'id="current"' : '') .'>'  
						. '<img src="'.$m_data[1].'" class="borderless"/>'
						. $this->Html->link(__($m_title, true), $m_url) . '</td>';
				}
			endforeach;
	   ?>
		</tr>
	</table>
  </div>
  <div id="content-wrap">
   
		<div id="sidebar">
<!--
		<h1>Importante</h1>
		<div align="center">	
			<?php echo $this->Html->image("/img/warning.png", array('class'=>'borderless')); ?>
		</div>
		<p align="justify">
			Revise</b> su carpeta de <b style='color: red;'>correo no deseado/basura (SPAM)</b>,
			nuestro sistema diariamente le envía información importante y es posible que se encuentre en esa carpeta.
			<br><br>
			También puede ver los correos electrónicos enviados en la sección <b>
<?php echo $this->Html->link(__('Mensajes', true), '/Mails/messages', array('style'=>'color: #2180BC;')); ?></b>
	 </p>
-->
<!--
		<h1>Sitio Web M&oacute;vil</h1>
		<p>
			<?php
 				echo $this->Html->link(
     			$this->Html->image("/img/iphone.png", array('class'=>'borderless')),
	     			"https://" . Configure::read('Futbol.serverWwwDomain') . "/futbolm", array('escape' => false)
					);
			?>
		</p>
-->
		<?php if (!$this->Session->read('Auth.User')){ ?>
		 	<h1>¿Ya es usuario?</h1>
			<ul class="sidemenu">
				<li>
					<b>
						<?php echo $this->Html->image("/img/lock.png", array('class'=>'borderless')); ?>
						<?php echo $this->Html->link(__('Entrar al sistema', true), '/users/login', array('style'=>'color: #2180BC;')); ?>
					</b>
				</li>
				<li>
					<?php echo $this->Html->image("/img/help.png", array('class'=>'borderless')); ?>
					<?php echo $this->Html->link(__('¿Olvidó su clave o usuario?', true), '/users/forgot', array('style'=>'color: #2180BC;')); ?>
				</li>
			</ul>
			
			<h1>¿Usuario Nuevo?</h1>
			<ul class="sidemenu">
				<li>
					<b>
					<?php echo $this->Html->image("/img/preppy.png", array('class'=>'borderless')); ?>
					<?php echo $this->Html->link(__('¡Regístrese aquí!', true), '/users/register', array('style'=>'color: #2180BC;')); ?>
					</b>
				</li>
			</ul>
		<?php } ?>  
		<?php if (!empty($pot_amount) && !empty($pot_amount[0])){
							 echo '<h1>'.__('Posiciones',true).'</h1>'; 
	  ?>
    	<p class="futbol_pot">
				<?php
					echo $this->Html->image('/img/medal_gold.png', array('class'=>'borderless'));
					echo "1: " . $pot_amount[0] . "<br>";
					echo $this->Html->image('/img/medal_silver.png', array('class'=>'borderless'));
					echo "2: " . $pot_amount[1] . "<br>";
					echo $this->Html->image('/img/medal_bronze.png', array('class'=>'borderless'));
					echo "3: " . $pot_amount[2] . "<br>";
					if ($this->Session->read('Auth.User.username')=="admin"){
						echo $this->Html->image('/img/medal_iron.png', array('class'=>'borderless'));
						echo "10%: " . $pot_amount[3] . "<br>";
					}
				?>
			</p>
    <?php } ?>  
		<h1>Hoy</h1>
				<p class="futbol_calendar">
					<font style='font-size: 11px;'><?php echo date('l');?><br></font> 
					<font style='font-size: 32px;'><?php echo date('d');?> <br></font>
					<font style='font-size: 13px;'><?php echo date('F Y');?><br></font>
					<font style='font-size: 8px;'><br></font>
					<font id="clock" style='font-size: 11px;'></font> 
				</p>
				
      <h1>Páginas</h1>
      <ul class="sidemenu">
        <li>
			  <?php echo $this->Html->image('/img/marca.png', array('class'=>'borderless')); ?>
				<a href="http://www.marca.com/">Marca</a></li>
        <li>
			  <?php echo $this->Html->image('/img/flags/VE.gif', array('class'=>'borderless')); ?>
				<a href="http://www.meridiano.com.ve/">Meridiano</a></li>
        <li>
			  <?php echo $this->Html->image('/img/flags/ES.gif', array('class'=>'borderless')); ?>
				<a href="http://www.lfp.es/">La Liga</a></li>
        <li>
			  <?php echo $this->Html->image('/img/flags/GB.gif', array('class'=>'borderless')); ?>
				<a href="http://www.premierleague.com">Premier League</a></li>      
        <li>
			  <?php echo $this->Html->image('/img/flags/IT.gif', array('class'=>'borderless')); ?>
				<a href="http://www.legaseriea.it">Calcio Serie A</a></li>
        <li>
			  <?php echo $this->Html->image('/img/flags/DE.gif', array('class'=>'borderless')); ?>
				<a href="http://www.bundesliga.com">Bundesliga</a></li>
        <li>
			  <?php echo $this->Html->image('/img/fifa_mini.jpg', array('class'=>'borderless')); ?>
				<a href="http://es.fifa.com/worldfootball/index.html">FIFA</a></li>
      </ul>
  	
		<?php if (Configure::read('Futbol.environment')=="production" || true){ ?>
      <h1>Sitio Seguro</h1>
			<p>
	     	<?php echo $this->element('le-ssl'); ?>
			</p>
		<?php } ?>
    
     <!-- <h1>Compatibilidad</h1>
		   <p>
        <?php	echo $this->element('compatible'); ?>
      </p>
			<br> -->
		</div>
		
    <div id="main">
		<?php 
				$flash = $this->Session->flash() . $this->Session->flash('auth') .
					$this->Session->flash('error') . $this->Session->flash('email');
				if ($flash != "") echo "<br>";
				echo $flash;

				$crumbs = $this->Html->getCrumbs(' > ',null); 
				if ($crumbs) echo "<p>$crumbs</p>";
    
				if ($this->Session->read('Auth.User') 
						&& $this->Session->read('Auth.User.username')!='admin' 
							&& Configure::read('Futbol.surveyStatus')=='on'){
					echo $this->element('survey');
				}
	
				echo $content_for_layout; 
		?>			
		<br>
		</div>
	</div>
	<div id="footer">
     <p>
  		 &copy; 2011 - <?php echo date("Y"); ?>
		 <b>
		 <?php
				 echo $this->Html->link("Olaf Reitmaier Veracierta", "https://www.linkedin.com/in/olafrv/");
			?>
     </b>using
		 <a href="http://www.styleshout.com/"><strong>Styleshout</strong></a>, <a href="https://deleket.deviantart.com/gallery/"><strong>Deleket</strong></a>, <a href="http://www.fasticon.com/"><strong>Fast Icons</strong></a>, <a href="http://www.menucool.com/javascript-image-slider"><b>Menucool.com</b></a>
		 	<br>
			<?php
				echo $this->Js->writeBuffer(); // Write cached scripts
				echo $this->element('powered') . " " . Configure::version();
			?>
			&nbsp; - IP: 
			<?php 
				echo $_SERVER["REMOTE_ADDR"] . " "; 
				echo $this->Html->image('/img/flags/' . Configure::read('Futbol.userIpCountry') . '.gif', 
					array('class'=>'borderless'));
			?>
			&nbsp;- Date: <?php echo date(DATE_RFC822); ?>
     </p>
     <?php
      	if (Configure::read('debug')){
				echo "<br><br><table align='center' bgcolor='black'><tr><td align='center'>";
				echo $this->element('sql_dump'); 
				echo "</td></tr></table>";
			}
     ?>
   </div>
  </div>
 </body>
</html>
