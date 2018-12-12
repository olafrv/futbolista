<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
$alert_class = 'info';
switch($params['class']){
	case 'error':
		$alert_class = 'danger';
		break;
}
?>
<div class="<?= h($class) ?> alert alert-<?= $alert_class ?>" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <?= $message ?>
</div>
