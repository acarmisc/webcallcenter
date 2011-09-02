<table>

	<tr>
		<th>Mese</th>
		<th>Prodotto</th>
		<th>quantita</th>
	</tr>
	



<?php

	$this->db->select('prodotto.nome, store_globale.*, store_mesi.nome_mese');

	if($this->session->userdata('current_farmacia')!=''){
		$this->db->where('farmacia_id',$this->session->userdata('current_farmacia'));
	}

	$this->db->join('prodotto','prodotto.id = store_globale.prodotto_id');
	$this->db->join('store_mesi','store_mesi.id = store_globale.mese_id');	
	$this->db->order_by('mese_id');
	$query = $this->db->get('store_globale');
	
	foreach($query->result() as $r){?>
	
	<tr>
		<td><?= $r->nome_mese ?></td>
		<td><?= $r->nome ?></td>
		<td><?= $r->quantita ?></td>
	</tr>
	
		
<?php	}	?>

</table>