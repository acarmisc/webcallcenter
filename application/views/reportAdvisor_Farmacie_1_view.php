<?php
$tableStyle=array(
	'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
	'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
	'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
	'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
);
$this->table->set_template($tableStyle);
$this->table->set_heading($intestazioneTabella);
foreach($righe as $riga){
	$this->table->add_row($riga);
}
echo $this->table->generate();