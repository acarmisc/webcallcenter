<?php
	$this->db->select('advisor.id');
	$this->db->join('advisor','advisor.utente_id = utente.id');
	$query = $this->db->get_where('utente',array('utente.id'=>$info_utente['id']));
	foreach($query->result() as $r){
		$adv_id	= $r->id;
	}
?>

<style type="text/css">

.spedizioni-window {

	border-color: #d0d0d0;

	border-width: 1px;

	border-style: solid;

	padding: 10px;

	background-color: white;

	position: absolute;
	width: 900px;
	top: 10px;
	left: 10px;
	z-index: 1000;

}

.my-overlay {
	background: black;
	opacity: 0.7;
	width: 100%;
	height: 100%;
	position: absolute;
	left: 0px;
	top: 0px;
}

</style>
<!-- copia online -->

<script>
	function openSpedizioni(id){
		$.ajax({  
			type: 'GET',  
			url: '?c=magazzino&m=advisor_view&id='+id,
			success: function(responseText) { 
				$('body').append('<div id="my-overlay" class="my-overlay"></div>');
				$('#toolbar').append('<div id="spedizioni-window" class="spedizioni-window">ciao mondo!</div>');
				$('#spedizioni-window').html(responseText);
			}
		});	
	}


function editIt(id,t,v,adv){
if (confirm("Continuare con la modifica?")) {
	$.ajax({  
		 type: 'GET',  
		 url: '?c=magazzino&m=update_order&id='+id+'&t='+t+'&v='+v+'&adv='+adv,
		 success: function(responseText) { 
			$('#spedizioni-window').html(responseText);
		}
		});	
}
}
</script>

<!-- fine copia -->
<div id="toolbar" class="">
	<h2>Attivit&agrave;</h2>
	<?php 
	if(count($voci_toolbar)==0) return;
	foreach($voci_toolbar as $voce){
		echo "<li>".$voce."</li>";
	}
	
	?>
<!-- copia online -->	
	<br />
	<li><a href="#" onclick="openSpedizioni('<?= $adv_id ?>')">Spedizioni</a></li>
	
<!-- fine online -->	
	<br />
	<h2>Risorse</h2>
	<li><?= anchor('manuali','manuali')?></li>
</div>


<?php 
/*
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
*/
?>