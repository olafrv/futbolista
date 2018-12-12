<h1>Términos y Condiciones</h1>

<p align="center">
	<ul>
		<li><a href="#r1">1: ¿Quiénes pueden disfrutar de nuestros servicios?</a></li>
		<li><a href="#r2">2: ¿Qué servicios ofrecemos en nuestra página?</a></li>
		<li><a href="#r3">3: ¿Cuánto cuestan los servicios ofrecidos en la página Web?</a></li>
		<li><a href="#r4">4: ¿Hasta cuándo puedo introducir predicciones para un partido?</a></li>
		<li><a href="#r5">5: ¿Cómo se cálculan los puntos acumulados en un partido?</a></b>
		<li><a href="#r6">6: Falla masiva y situaciones de fuerza mayor</a></b>
		<li><a href="#r7">7: Auditoría y privacidad de datos</a></b>
	</ul>
</p>

<h3>Actualizadas el 8 de Junio de 2018</h3>

<table>
<tr>	
  <th>Nº</th>
	<th>Enunciado</th>
</tr>

<tr class="row-a">
   <td style="text-align: center;">1<a name="r1"/><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
   <td>Toda persona natural mayor de edad puede <?php echo $this->Html->link('registrarse', '/users/register'); ?> en el sistema convirtiéndose en usuario previa aceptación tanto de éstos terminos y condiciones como de las <?php echo $this->Html->link('políticas de privacidad', '/users/privacy'); ?>.
</td>
</tr>

<tr class="row-b">	
	<td style="text-align: center;">2<a name="r2"/><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
	<td>
La página Web <?php echo $this->Html->link(Configure::read('Futbol.serverSslUrl'),'/'); ?> es un sistema Web  
que ofrece el servicio de almacenamiento de datos estadísticos de las predicciones y los 
resultados de los partidos de <?php echo $this->Html->link('competencias', '/competitions/info'); ?> de futbol.
   </td>
</tr>

<tr class="row-a">
   <td style="text-align: center;">3<a name="r3"><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
	<td>Para mayor información sobre el costo de los servicios ofrecidos por el sistema visite la sección de
	<?php echo $this->Html->image("/img/credit_cards.png", array('class'=>'borderless')); ?>
	<?php echo $this->Html->link('Pagos', '/payments/show'); ?>, sólo los usuarios que han pagado por nuestros
  servicios se consideran usuarios <b>activos</b>.
</tr>

<tr class="row-b">
   <td style="text-align: center;">4<a name="r4"><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
	 <td>
Un usuario podrá introducir su <font color="red">predicción para un partido hasta las 11:59 PM (VET -4:00 GMT)</font>
del día anterior a la fecha oficial del partido, después de ésta hora podrá visualizar la predicciones introducida
por los demás usuarios, antes de ésta hora sólo visualizar la distribución (% de usuarios) de las predicciones del
partido en cuestión.</font>
</td>
</tr>

<tr class="row-a">
	<td style="text-align: center;">5<a name="r5"/><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
	<td>
La cantidad de puntos acumulados por un usuario en un partido en una competencia es igual a
al total de <b>puntos básicos</b> multiplicado por el <b>bono (multiplicador)</b> sujetos a las
siguientes condiciones:
<table>
  <tr>
    <th>Tipo de Puntos</th>
    <th>¿Cómo se obtienen?</th>
  </tr>
  <tr class="row-a">
    <td>
      <b>Puntos básicos</b>
    </td>
	 <td>Para cada partido hasta los primeros 120 minutos (90 min. regulares + 30 min. de prórroga si la hay) se dará uno y solo uno de los siguientes escenarios con el cuál se determinará los puntos obtenidos: 
<table>
 <tr>
  <th>Caso</th>
  <th>Puntos</th>
 </tr>
 <tr class="row-a">
  <td>Acierto del <b>resultado</b> (número de goles) de ambos equipos (sea empate o no).</td>
  <td>3</td>
 </tr>
 <tr class="row-b">
  <td>Acierto del <b>ganador</b> (no aplica en empate) y el <b>resultado</b> (número de goles) <b>de cualquier equipo</b>.</td>
  <td>1.5</td>
 </tr>
 <tr class="row-a">
  <td>Acierto del <b>ganador o empate</b> pero <b>sin el resultado</b> (número de goles).</td>
  <td>1</td>
 </tr>
 <tr class="row-b">
  <td>Ninguno de los anteriores</td>
  <td>0</td>
 </tr>
</table>
   </td>
  </tr>
  <tr class="row-a">
    <td>
      <b>Bono (Multiplicador)</b>
    </td>
    <td>
Cada partido podrá opcionalmente a criterio de los administradores
tener un bono que <b>multiplicará dos (2) o más veces los puntos básicos</b> obtenidos 
en el partido, el cual será definido antes de la fecha oficial del primer partido
de la fase.
    </td>
  </tr>
</table>

El total de puntos acumulados por los usuarios durante una competencia permiten ordernarlos en posiciones, pueden haber <b>usuarios con la misma cantidad de puntos</b>, y por lo tanto, <b>ocupando (compartiendo) la misma posición</b>.

<div align="center">
<table>
 <tr>
   <th>Posición/Lugar</th>
 </tr>
 <tr class="row-a">
   <td><?php echo $this->Html->image('/img/medal_gold.png', array('class'=>'borderless')); ?><b>1er Lugar</b></td>
 </tr>
 <tr class="row-b">
   <td><?php echo $this->Html->image('/img/medal_silver.png', array('class'=>'borderless')); ?><b>2do Lugar</b></td>
 </tr>
 <tr class="row-a">
   <td><?php echo $this->Html->image('/img/medal_bronze.png', array('class'=>'borderless')); ?><b>3er Lugar</b></td>  
 </tr>
 <table>

	</td>
</tr>
</table>
</div>
</td>
</tr>

<tr class="row-b">
   <td style="text-align: center;">6<a name="r6"/><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
   <td>
En caso una <b>falla masiva</b> de los servicios provistos por el sistema durante una competencia 
por un <b>lapso mayor de 6 horas continuas en un mismo dia</b> antes de un partido, al dia siguiente
no serán introducidos resultados, evitando que se contabilicen puntos debido a que no se dieron
las condiciones necesarias para garantizar el servicio a todos los usuarios del sistema.
<br><br>
Si la falla persiste por más tiempo y afecta a más días de celebración de partidos,
el <b>administrador del sistema</b> se reserva el derecho de <b>declarar una afectación
mayor del servicio</b> y en tal caso realizará un <b>reintegro individual</b>
a los usuarios por el monto que hayan pagado por los servicios afectados.
	</td>
</tr>

<tr class="row-a">
   <td style="text-align: center;">7<a name="r7"/><br/><br/><a href="#top"><?php echo __("Arriba",true); ?></a></td>
   <td>
<a name="audit"></a>
<p>
Los datos estadísticos de las predicciones y los resultados de los partidos de
<?php echo $this->Html->link('competencias', '/competitions/info'); ?> de futbol,
almacenados por el sistema son auditables en cualquier momento por cualquier usuario.
</p>
<p>
Cada día entre las 12:00 A.M y las 12:30 A.M (VET -4:00 GMT) el sistema generará un reporte y una firma
electrónica utilizando el algoritmo <a href="http://en.wikipedia.org/wiki/SHA-2">SHA de 256 bits</a>, los cuales,
son almacenados y publicados inmediatamente en la siguientes direcciones Web:
   <ul>
    <li>
      <?php echo $this->Html->link(__('REPORTES (Por Fecha)',true), '/audit/data/?C=N;O=D', array('target'=>'_blank')); ?>
    </li>
    <li>
      <?php echo $this->Html->link(__('FIRMAS (Por Fecha)', true), '/audit/signature/?C=N;O=D', array('target'=>'_blank')); ?>
    </li>
   </ul>
<p>Para determinar si un reporte, publicado aquí o presentado por un tercero, ha sido alterado 
debe recalcularse el valor de la firma electrónica de dicho reporte utilizando el algoritmo 
<a href="http://en.wikipedia.org/wiki/SHA-2">SHA de 256 bits</a> 
y compararlo con el valor de la firma electrónica de dicho reporte.
</p>
   </td>
</tr>

</table>
