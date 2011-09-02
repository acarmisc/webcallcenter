<script language="Javascript">

function richiestaAjax(url_sorgente,id_destinazione){
	var ajaxRequest = new XMLHttpRequest();
	try{
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
	         } catch (e){
	            return false;
	         }
		}
	}
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
      		var ajaxDisplay = document.getElementById(id_destinazione);
         	ajaxDisplay.innerHTML = ajaxRequest.responseText;
      	}
   }
   ajaxRequest.open("GET", url_sorgente, true);
   ajaxRequest.send(null);
}


</script>


<div id="core_space" class="">
	<h1>Risultati ricerca <span style="font-size:10px">(<?= sizeOf($risultati)?> risultati trovati)</span></h1>
	<div class="costrainer">
	<?php 
	echo form_open('',array('name'=>'formFarmacie'));
	$tableStyle=array(
		'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
		'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
		'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
		'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
	);
	$this->table->set_template($tableStyle);
	$this->table->set_heading('Cod.Cliente','Rag. Sociale','Indirizzo','CAP','Località','Prov.','Regione','Advisor','Agente','Stato');
	foreach($risultati as $farmacia){


		$valore=$farmacia[9];



		$this->table->add_row($farmacia);
	}
	echo $this->table->generate();
	echo form_close();


	
	echo '</div>';

?>	
	</div>
</div>	