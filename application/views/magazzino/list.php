<h3>Storico spedizioni</h3>
<table class="tableRisRicClass" width="960">
	<tr>
		<th width="20"></th>
		<th width="20"></th>		
		<th width="">Advisor</th>
		<th width="80">Data sped.</th>
		<th width="80">Arrivo stim.</th>		
		<th width="">Antistax</th>		
		<th width="">Dulcofibre</th>		
		<th width="">Rinogut</th>		
		<th width="">Buscopan</th>		
		<th width="">Zerinol Gola</th>
		<th width="">Totem</th>
		<th width="">Porta Rifl.</th>														
		<th width="50"></th>
	</tr>

<?php	foreach($orders as $r){ ?>
	<tr id="ship-<?= $r->id ?>" class="stat_<?= $r->segnalato ?>">
		<td align="center"><?php if($r->arrivato==0){echo img('img/magazzino/truck--arrow.png'); }else{ echo img('img/magazzino/truck.png'); }?></td>
		<td align="center"><?php if($r->segnalato==0){echo img('img/magazzino/tick-circle.png'); }else{ echo img('img/magazzino/minus-circle.png'); }?></td>		
		<td><?= $r->nome_completo ?>
		<span class="falseButton" onclick="openIt('magazzino-listwrapper','?c=magazzino&m=per_advisor_list&id=<?= $r->adv_id ?>')"><?= img('img/magazzino/cards-stack.png') ?></span>
		</td>
		<td align="center"><?= $r->data_spedizione ?></td>
		<td align="center"><?= $r->data_arrivo_presunto ?></td>		
		<td align="center"><?= $r->colli_antistax ?></td>
		<td align="center"><?= $r->colli_dulcofibre ?></td>
		<td align="center"><?= $r->colli_rinogut ?></td>
		<td align="center"><?= $r->colli_buscopan ?></td>
		<td align="center"><?= $r->colli_zerinol_gola ?></td>
		<td align="center"><?= $r->colli_totem ?></td>
		<td align="center"><?= $r->colli_portarif ?></td>			
		<td>
			<span class="falseButton" onclick="deleteIt(<?= $r->id ?>)"><?= img('img/magazzino/eraser.png') ?></span>
			<?php if($r->segnalato==0){}else{?> 
			<span class="falseButton" onclick="manageIt(<?= $r->id ?>)"><?= img('img/magazzino/arrow-turn-000-left.png') ?></span>
			<?php }?>
		</td>
	</tr>
<?php 
	
	if($r->segnalato!=0){
		$query1 = $this->db->get_where('magazzino',array('sonof'=>$r->id));
		if($query1->num_rows() > 0){
			foreach($query1->result() as $r1){ ?>
	
	
	<tr id="ship-<?= $r1->id ?>" class="stat_<?= $r1->segnalato ?> clildrow">
		<td align="center"><?php if($r1->arrivato==0){echo img('img/magazzino/truck--arrow.png'); }else{ echo img('img/magazzino/truck.png'); }?></td>
		<td align="center"><?php if($r1->segnalato==0){echo img('img/magazzino/tick-circle.png'); }else{ echo img('img/magazzino/minus-circle.png'); }?></td>		
		<td>	&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;	
		</td>
		<td align="center"><?= $r1->data_spedizione ?></td>
		<td align="center"><?= $r1->data_arrivo_presunto ?></td>		
		<td align="center"><?= $r1->colli_antistax ?></td>
		<td align="center"><?= $r1->colli_dulcofibre ?></td>
		<td align="center"><?= $r1->colli_rinogut ?></td>
		<td align="center"><?= $r1->colli_buscopan ?></td>
		<td align="center"><?= $r1->colli_zerinol_gola ?></td>
		<td align="center"><?= $r1->colli_totem ?></td>
		<td align="center"><?= $r1->colli_portarif ?></td>			
		<td>
			<span class="falseButton" onclick="deleteIt(<?= $r1->id ?>)"><?= img('img/magazzino/eraser.png') ?></span>
			<?php if($r1->segnalato==0){}else{?> 
			<span class="falseButton" onclick="manageIt(<?= $r1->id ?>)"><?= img('img/magazzino/arrow-turn-000-left.png') ?></span>
			<?php }?>
		</td>
	</tr>		
	
		
<?php			}
		
		}
	}

} ?>	
</table>
