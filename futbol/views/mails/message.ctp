<?php

if ($message["Mail"]["html"]==1){
	echo "<div align='left'>";
	echo $message["Mail"]["body"];
	echo "</div>";
}else{
	echo "<div align='left'><pre>";
	echo str_replace(array("\r\n", "\n", "\r"), '<br>', $message["Mail"]["body"]);
	echo "<pre></div>";
}
?>
