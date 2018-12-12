<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
	echo $this->Html->charset(); 
	echo $this->Html->script('jquery-1.5.1.min'); // Include jQuery library
	echo $this->Html->script('jquery-ui-1.8.12.custom.min'); // Include jQuery library
	echo $this->Html->css('/css/redmond/jquery-ui-1.8.12.custom');
?>
<title><?php echo $page_title; ?></title>
<?php if (Configure::read() == 0) { ?>
<meta http-equiv="Refresh" content="<?php echo $pause?>;url=<?php echo $url?>"/>
<?php } ?> 
</head>
<body>
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="margin-left: auto; margin-right: auto; width: 50%; margin-top: 20px; padding: 0 .7em;"> 
				<p>
					<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
						<strong><?php if (isset($this->Time)) echo '[' . $this->Time->niceShort() . '] '; ?></strong>
						<div align="center">
								<?php //echo $this->Html->link($message, $url); ?>
								<?php echo "<a href='" . Configure::read('serverUrl') . $url. "'>$message</a>"; ?>
						</div>
						<p align="right"><i><?php __('Haga clic en el mensaje para continuar'); ?></i></p>
				</p>
			</div>
		</div>
</body>
</html>
