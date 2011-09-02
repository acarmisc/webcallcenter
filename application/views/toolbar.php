<?php ?>
<div id="toolbar" class="">
	<h2>Attivit&agrave;</h2>
	<?php 
	if(count($voci_toolbar)==0) return;
	foreach($voci_toolbar as $voce){
		echo "<li>".$voce."</li>";
	}
	
	?>
	<br />
	<h2>Risorse</h2>
	<li><?= anchor('manuali','manuali')?></li>
</div>