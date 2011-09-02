<div id="core_space" class="">
<h1>Campagne richieste</h1>
<?php 
$tableStyle=array(
	'table_open' 		=> '<table id="tableRisRic" name="tableRisRic" class="tableRisRicClass">',
	'heading_row_start'	=> '<tr class="tableRisRic_rigaHeadClass">',
	'row_start'			=> '<tr class="tableRisRic_rigaDispariClass">',
	'row_alt_start'		=> '<tr class="tableRisRic_rigaPariClass">'
);
$this->table->set_template($tableStyle);
$this->table->set_heading($tab_richieste['intestazione']);
$this->table->add_row($tab_richieste['righe'][0]);
$this->table->add_row($tab_richieste['righe'][1]);
echo $this->table->generate();
$this->table->clear();
?>
<h1>Statistiche sulle farmacie</h1>
<?php 
$this->table->set_template($tableStyle);
$this->table->set_heading($tab_visite['intestazione']);
$this->table->add_row($tab_visite['riga']);
echo $this->table->generate();
$this->table->clear();
?>
<h1>Statistiche sulle attivita</h1>
<?php 
$this->table->set_template($tableStyle);
$this->table->set_heading($tab_attivita['intestazione']);
$this->table->add_row($tab_attivita['riga']);
echo $this->table->generate();
$this->table->clear();
?>



</div>
