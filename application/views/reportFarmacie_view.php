<div id="core_space" class="">
<?php
$this->session->set_flashdata('from','attivita');
echo '<h1>'.$titolo.'</h1>';


echo form_open('reportFarmacie/index',array('name'=>'formStatistiche'));
echo '<span style="float: right;">';
echo '<input type="submit" name='.$pulsante_csv.' value="Download csv" />';
echo '</span>';
echo form_close();
		$tableStyle=array(
			'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
			'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
			'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
			'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
			);
$this->table->set_template($tableStyle);
$this->table->set_heading($intestazione_tabella);

foreach($statistiche as $at){

		//array_shift($at);
		$this->table->add_row($at);
		}
		echo $this->table->generate();

		
?>
</div>