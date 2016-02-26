<?php
	require '../config.php';
?>
function openAccessories(lineid) {
	
	$('tr[accessory-line-id='+lineid+']').toggle();
	
}
