<?php
$this->session->set_flashdata('from','attivita');
echo '<h1>'.$titolo.'</h1>';
//echo form_open('',array('name'=>'formAttivita'));
		$tableStyle=array(
			'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
			'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
			'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
			'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
			);
$this->table->set_template($tableStyle);
$this->table->set_heading($intestazione_tabella);

foreach($attivita as $at){

		array_shift($at);
		$this->table->add_row($at);
		}
		echo $this->table->generate();
		
		echo '<p align="center">';
/*		$attributi=array(
			'name'	=> 'dettaglioAttivita',
			'id'	=> 'formAttivitaDettaglio'
		);
		echo form_submit($attributi,'Dettaglio Attivita');
		if($permesso_modifiche){
			$attributi=array(
				'name'	=> 'nuovo',
				'id'	=> 'formAttivitaNuovo'
			);
			echo form_submit($attributi,'Nuova Attivita');
		}*/
		echo '</p>';
//		echo form_close();

?>
