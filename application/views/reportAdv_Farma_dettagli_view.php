
<div id="core_space" class="">
	<h1>Report clienti per advisor</h1>
	<div class="costrainer">
	<?php 
if($sceltaEffettuata){
	echo form_open('reportAdvisor_Farmacie/index',array('name'=>'frmAdvFarmCsv'));
	echo '<span style="float: right;">';
	echo '<input type="hidden" name="advisor_id_txt" value="'.$advisorCorrente.'" />';
	echo '<input type="submit" name="csv_export" value="Download csv" />';
	echo '</span>';
	echo form_close();
}
	$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formRicAttAdv',
				'name'		=> 'formRicAttAdv',
				'class'		=> 'formRicAttAdv'
			);
	echo form_open('reportAdvisor_Farmacie',$attributi);
	echo form_label('Advisor: ','advisor');
	$attributi='id="frmRcAttAdv_advisor"  onChange="submit()"';
	echo form_dropdown('advisor_corrente',$advisors,$advisorCorrente,$attributi);	
	echo form_close();
	if($sceltaEffettuata){
		$tableStyle=array(
			'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
			'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
			'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
			'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
		);
		$this->table->set_template($tableStyle);
		foreach($risultati as $nome => $gruppo){
			echo "<h2>$nome</h2>";
			$this->table->set_heading($intestazione_tabelle);
			foreach($gruppo as $farmacia){
				$this->table->add_row($farmacia);
			}
			echo $this->table->generate();
			echo $this->table->clear();
		}
	}

	
	echo '</div>';

?>	
	</div>
</div>	