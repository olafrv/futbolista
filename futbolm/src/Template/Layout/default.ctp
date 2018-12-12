<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
  <head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<?= $this->Html->meta('icon', '/img/favicon.ico', ['type' => 'icon']); 
		?>
    <?= $this->fetch('meta') ?>
    <title>
        Futbolista (<?= $this->request->host() ?>)
    </title>
		<?= $this->Html->css('/js/bootstrap-3.3.6-dist/css/bootstrap.min.css'); ?>
		<?= $this->Html->css('/js/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css'); ?>
    <?= $this->fetch('css') ?>
		<?= $this->Html->script('/js/jquery-1.12.0.min.js'); ?>
		<?= $this->Html->script('/js/bootstrap-3.3.6-dist/js/bootstrap.min.js'); ?>
    <?= $this->fetch('script') ?>
</head>

<body>
	<div class="container">
		<?= $this->element('menu'); ?>
	  <?= $this->Flash->render() ?>
		<?= $this->Flash->render('auth'); ?>
	</div>
	<?= $this->fetch('content') ?>
	<br>
	<div align="center">
		<b><?=__('Now'); ?>:</b> <?php echo date(DATE_RFC822); ?>
	</div>
</body>
</html>
