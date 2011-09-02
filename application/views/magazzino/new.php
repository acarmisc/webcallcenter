<h3>Nuova spedizione</h3>
<form name="new-order" action="?c=magazzino&m=save_order" method="post">
<?= form_hidden('sonof',$_GET['id']) ?>
<p><label>Advisor</label>
<?php
	$data = array();

	$this->db->select('advisor.id, utente.nome_completo');
	$this->db->join('utente','utente.id = advisor.utente_id');
	$this->db->where('advisor.deleted_at',null);
	$query = $this->db->get('advisor');
	
	foreach($query->result() as $r){
	
		$data += array($r->id=>$r->nome_completo); 
			
	}
	
	echo form_dropdown('codice_adv',$data);
	
?>
 <label>Data spedizione</label>
<input type="text" size="10" name="data_spedizione" class="mydates" />

 <label>Data pres. arrivo</label>
<input type="text" size="10" name="data_arrivo_presunto" class="mydates" />


</p>

<p><label>Colli Antistax</label>
<input type="text" size="3" name="colli_antistax" />

<label>Colli Dulcofibre</label>
<input type="text" size="3" name="colli_dulcofibre" />

<label>Colli Rinogutt</label>
<input type="text" size="3" name="colli_rinogut" />

<label>Colli Buscopan</label>
<input type="text" size="3" name="colli_buscopan" />

<label>Colli Zerinol Gola</label>
<input type="text" size="3" name="colli_zerinol_gola" /></p>

<label>Colli Totem</label>
<input type="text" size="3" name="colli_totem" /></p>


<label>Colli Porta R.</label>
<input type="text" size="3" name="colli_portarif" /></p>


<p align="center">
<span class="falseButton" onclick="$('.intable-dialog').remove()">Annulla</span> 
<input type="submit" value="salva" /></p>

</form>