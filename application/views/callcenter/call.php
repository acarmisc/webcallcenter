<!--

<pre>
	<?= print_r($attivita) ?>
</pre>
-->
<?php foreach($farmacia as $f){ ?>

<div id="" class="callcenter-farmacia-view">
<h1>Chiamata in corso <small>per <?= $f->denominazione ?></small></h1>
	<div id="" class="pan-1">
		
		<div id="" class="edit-farma">
			<span class="edit_locally"><?= img('img/callcenter/wand.png') ?></span><br /><br />
			<span class="update-b" style="display:none">
				<button onclick="updateFarma()">Salva</button>
			</span>
		</div>
		
		<form id="farma-det">
			<?= form_hidden('id',$f->id) ?>
		<p><label>denominazione</label> 
		<input type="text" disabled="disabled" value="<?= $f->denominazione ?>"></p>
		
		<p><label>indirizzo</label>
		<input type="text" class="pot-edit" disabled="disabled" value="<?= $f->indirizzo ?> <?= $f->cap ?> <?= $f->localita ?> <?= $f->provincia_id ?>"></p>
		<p><label>telefono</label> <input name="numtel" type="text" class="pot-edit"  disabled="disabled" value="<?= $f->numtel ?>"></p>
			<p><label>fax</label> <input name="numfax" type="text" class="pot-edit"  disabled="disabled" value="<?= $f->numfax ?>"></p>		
		<label>e-mail</label> <input name="email" type="text" class="pot-edit" disabled="disabled" value="<?= $f->email ?>"></p>
		</form>
		<?php
			$adv = $f->advisor_id;
			$this->db->select('utente.nome_completo, utente.email');
			$this->db->join('utente', 'utente.id = advisor.utente_id');
			$qadv = $this->db->get_where('advisor', array('advisor.id'=>$adv));
			foreach($qadv->result() as $radv){ ?>

			<p><label>Advisor</label> <input type="text" disabled="disabled" value="<?= $radv->nome_completo ?>">
			<?php if(isset($radv->email)){?>
			<a href="mailto:<?= $radv->email ?>"><?= img('img/callcenter/mail--arrow.png') ?></a></p>	
			<input type="hidden" name="adv_email" value="<?= $radv->email ?>" />
			<?php } ?>
		<?php
			}
		?>

		<h3>Storecheck</h3>
		<div id="storecheck-info" class="">
		<?php
			$this->db->order_by('id DESC');
			$this->db->limit(1);
			$query_sc = $this->db->get_where('reportstorecheck',array('farmacia_id'=>$f->id));
			foreach($query_sc->result() as $sc){ ?>

			<p><label>Collaboratore</label> <input type="text" id="c_rif" name="collaboratore_riferimento" value="<?= $sc->collaboratore_riferimento ?>" /><span id="update-collaboratore"></span></p>
			<p><label>Giorno chiusura</label> <input type="text" disabled="disabled" value="<?= $sc->giorno_chiusura ?>" name="collaboratore_riferimento" style="width:90px" /> <label>Aperto 24h</label> <?php if($sc->aperto24h==0){echo 'no';}else{echo 's&igrave';} ?></p>
			<p><label>Titolare</label> <input type="text" value="<?= $sc->titolare ?>"disabled="disabled" name="titolare" /></p>
			<p><label>Note</label><br /> <textarea rows="3" cols="50"><?= $sc->note ?></textarea></p>

			<button onclick="updateCollaboratore(<?= $sc->id ?>,$('#c_rif').val())">Aggiorna</button>
		<?php } ?>										
		</div>		

		
		
		<h3>Contatto</h3>
		<span class="long-value">
			<?= $f->contatto ?> <?= $f->numtel_contatto ?> <?= $f->email_contatto ?>
		</span>
		
		<h3 class="falseButton" onclick="$('#altro-space').slideToggle()">Altro</h3>
		<div id="altro-space" class="" style="display:none">
			<p><label>linea</label> <input type="text" disabled="disabled" value="<?= $f->linea_id ?>"> <br />
			<label>microbrick</label> <input type="text" disabled="disabled" value="<?= $f->microbrick_id ?>"><br />
			<label>creazione</label> <input type="text" disabled="disabled" value="<?= $f->created_at ?>"></p>
			
			<p><label>stato</label> <input type="text" disabled="disabled" value="<?= $f->stato ?>"><br />
			<label>isf</label> <input type="text" disabled="disabled" value="<?= $f->isf ?> <?= $f->num_isf ?>"></p>
			
			<p><label>flusso di lavoro</label> <input type="text" disabled="disabled" value="<?= $f->flusso_lavoro ?>"></p>
			
			<p><label>note Boehringer</label>
			<input type="text" disabled="disabled" value="<?= $f->note_boehringer ?>"></p>
			
			<p><label>note</label>
			<textarea disabled="disabled" cols="40"><?= $f->note ?></textarea></p>
		</div>
	</div>
	
	<div id="" class="pan-2">
		<h2>Dettagli</h2>		
	<?php foreach($attivita as $a){ ?>

		<?php
		
			if($a->tipoevento_id != 0){
				$qe = $this->db->get_where('tipoevento',array('id'=>$a->tipoevento_id));
				$re = $qe->result();
			}
		
		?>
	<form id="act_details" onsubmit="return false;">
		<p><label>id</label> <input type="text" disabled="disabled" name="act_id" value="<?= $a->id ?>"></p>
		<p><label>Appuntamento</label> <input type="text" disabled="disabled" name="act_date" value="<?= $a->giorno ?> <?= $a->ora_inizio ?>"></p>
		<p><label>Stato</label> <input type="text" disabled="disabled" value="<?= $a->stato ?>"></p>
		<p><label>Attivit&agrave;</label> <input type="text" disabled="disabled" value="<?= $a->nome ?>"></p>
		<p><label>Evento</label> <input type="text" disabled="disabled" value="<?php if(isset($re[0]->nome)){echo $re[0]->nome;}?>"></p>	</form>		
		
	<?php } ?>
	
		<h3>Note chiamata</h3>
		<form name="report_chiamata" action="?c=callcenter&m=save_call" method="post">
		
			<?= form_hidden('id',$cid) ?>			
			<?= form_hidden('stato',0) ?>
			
			<?php
				$query_c = $this->db->get_where('calling',array('id'=>$cid));
				foreach($query_c->result() as $r_c){
					
				}
			?>
			
			<p><label>esito</label><?= form_dropdown('esito',array(''=>'scegli...','confermato'=>'confermato','rimandato'=>'rimandato','non trovato'=>'non trovato'),$r_c->esito) ?> 			<a href="#" onclick="openQ(<?= $cid ?>)">Compila questionario</a></p>

			<p><textarea name="note_chiamata" rows="2" cols="50"><?= $r_c->note_chiamata ?></textarea></p>
			
			<p>
			<?php if(!isset($_GET['disabled'])){?>
				<input type="submit" value="salva" />
			<?php }else{ ?>
			<a href="?c=callcenter&m=history">&#8249; torna allo storico</a>
			<?php } ?>
			

			
			</p>
			
		</form>
	</div>
<?php if(!isset($_GET['disabled'])){?>
<p><a href="?c=callcenter&m=close_call">&#8249; indietro</a> <span class="suggestion">(torna alla lista senza impostare un esito)</span></p>
<?php } ?>
</div>

<?php	} ?>

<div id="tester" class=""></div>
