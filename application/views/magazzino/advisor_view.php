<!-- <div id="" class="" style="text-align:right;">
<a href="#" onclick="$('#my-overlay').fadeOut().remove();$('#spedizioni-window').remove();">esci <?= img('img/magazzino/door-open-in.png') ?></a>
</div> -->

<div id="magazzino-listwrapper" class="magazzino-listwrapper">    
   <a href="/" onclick="">indietro <?= img('img/magazzino/door-open-in.png') ?></a>

<h2>Storico spedizioni</h2>
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

<?php  
	foreach($orders as $r){ ?>
	<tr
		<?php if($r->segnalato==1): ?>
		 class="row-segnalato" 
		<?php endif; ?>
	>
		<td align="center"><?php if($r->arrivato==0){echo img('img/magazzino/truck--arrow.png'); }else{ echo img('img/magazzino/truck.png'); }?></td>
		<td align="center"><?php if($r->segnalato==0){echo img('img/magazzino/tick-circle.png'); }else{ echo img('img/magazzino/exclamation-octagon-frame.png'); }?></td>		
		<td><?= $r->nome_completo ?></td>
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
		<?php if($r->segnalato==0): ?>
			<span class="falseButton" onclick="editIt('<?= $r->id ?>','segnalato',1,'<?= $r->codice_adv ?>')">segnala</span>
		<?php endif; ?>
		<?php if($r->arrivato==0): ?>
			 <span class="falseButton" onclick="editIt('<?= $r->id ?>','arrivato',1,'<?= $r->codice_adv ?>')">arrivato</span>
		<?php endif; ?>
		</td>
	</tr>
<?php } ?>	
</table>

</div>


<!-- <div id="" class="" style="text-align:right;">
<a href="#" onclick="$('#my-overlay').fadeOut().remove();$('#spedizioni-window').remove();">esci <?= img('img/magazzino/door-open-in.png') ?></a>
</div> -->


