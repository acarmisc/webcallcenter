
<div id="core_space" class="">
	<h1>Assegnazione Farmacie-Advisor</h1>
	<div class="costrainer">
	<?php 
	$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formRicAttAdv',
				'name'		=> 'formRicAttAdv',
				'class'		=> 'formRicAttAdv'
			);
	echo form_open('assegnazioneFarmacie',$attributi);
	echo form_label('Advisor: ','advisor');
	$attributi='id="frmRcAttAdv_advisor"  onChange="submit()"';
	echo form_dropdown('advisor_corrente',$advisors,$advisorCorrente,$attributi);	
	echo form_close();
	if($sceltaEffettuata){
		echo form_open('assegnazioneFarmacie',array('name'=>'formFarmacie'));
		$tableStyle=array(
			'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
			'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
			'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
			'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
		);
		$this->table->set_template($tableStyle);
		$this->table->set_heading('Cod.','Rag. Sociale','Indirizzo','CAP','Località','Prov.','Advisor','Nuovo',nbs());
		foreach($risultati as $farmacia){
	
	
			//$valore=$farmacia[8];
	
	
	
			$this->table->add_row($farmacia);
		}
		echo $this->table->generate();
		echo form_close();
	}

	
	echo '</div>';

?>	
	</div>
</div>	