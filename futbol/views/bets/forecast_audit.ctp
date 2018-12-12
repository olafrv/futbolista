<h1>Reporte de Auditoría de Predicciones</h1>
<p>
<br/>
<strong><?php echo __('Firma',true); ?> SHA-1 (256 bits):</strong> <?php echo $signature; ?>
<br/>
<br/>
<strong><?php echo __('Descargar',true); ?>:&nbsp;</strong>
<?php echo $this->Html->link(__('[Datos]',true), $fileurl) ?>
<?php echo $this->Html->link(__('[Firma]',true), $sign_fileurl) ?>
</p>
<pre><?php echo $text; ?></pre>
