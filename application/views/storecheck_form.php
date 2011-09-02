<!--
Form per lo storecheck

i campi qui presenti devono finire dentro il db... voto per puntare a mostrare una insert più che una get dei dati, perché non posso valorizzare i value se non so come si chiamano i campi :(

i servizi vengono richiamati da un'altra view per comodità... non so se preferisci metterli come si diceva in un unico campo testo, con una mega stringona separati da virgola o simile magari.
-->

<link rel="stylesheet" media="screen" type="text/css" href="<?= base_url() ?>css/default.css" />
<link rel="stylesheet" media="screen" type="text/css" href="<?= base_url() ?>css/cupertino/jquery-ui-1.7.2.custom.css" />
<link rel="stylesheet" media="print" type="text/css" href="<?= base_url() ?>css/print.css" />
<script src="<?= base_url() ?>js/jquery.js"></script>
<script src="<?= base_url() ?>js/jquery-ui.js"></script>
<script src="<?= base_url() ?>js/pharma3d.js"></script>

<script >

	$(document).ready(function() {
		$('.date_fields').datepicker({dateFormat: 'dd-mm-yy' });
	});
	

</script>

<h1>Store Check</h1>

<form name="storecheck_form" class="formRicercaFarmaciaClass" action="#" method="POST">
<?php echo form_hidden('reportstorecheck_id',$storecheck['reportstorecheck_id']);?>
 <?php echo form_hidden('farmacia_id',$storecheck['farmacia_id']);?>

<p>
	<?= form_label('N. collaboratori laureati') ?>
	<?= form_input(array('name'=>'collaboratori_laureati','size'=>"2",'id'=>"",'value'=>$storecheck['collaboratori_laureati']));?>
 
	<?= form_label('N. collaboratori non laureati') ?>
	<?= form_input(array('name'=>'collaboratori_non_laureati','size'=>"2",'id'=>"",'value'=>$storecheck['collaboratori_non_laureati']));?>
<br />
	<?echo form_label('Fascia mq. negozio'); ?>
	<?= form_dropdown('fascia_negozio', $fasceNegozio,array_search($storecheck['fascia_negozio'],$fasceNegozio));?>

	<?= form_label('Mq calpestabili') ?>
	<?= form_dropdown('mq_calpestabili', $fasceCalpestabili,array_search($storecheck['mq_calpestabili'],$fasceCalpestabili));?>
	
	
	<?= form_label('N. vetrine finestra') ?>
	<?= form_input(array('name'=>'n_vetrine_finestra','size'=>"2",'id'=>"",'value'=>$storecheck['n_vetrine_finestra']))?>

	<?= form_label('N. vetrine da terra') ?>
	<?= form_input(array('name'=>'n_vetrine_da_terra','size'=>"2",'id'=>"",'value'=>$storecheck['n_vetrine_da_terra']))?>
<br />
	<h4>Tipo di insegna</h4>
	<?= form_label('Tradizionale') ?>	
	<?= form_checkbox('insegna_tradizionale', 'insegna_tradizionale',$storecheck['insegna_tradizionale']?true:false) ?>

	<?= form_label('Elettronica') ?>	
	<?= form_checkbox('insegna_elettronica', 'insegna_elettronica',$storecheck['insegna_elettronica']?true:false) ?>

	<?= form_label('Con accessori') ?>	
	<?= form_checkbox('insegna_accessori', 'insegna_accessori',$storecheck['insegna_accessori']?true:false) ?>

	<?= form_label('Appartenenza a catene') ?>	
	<?= form_checkbox('insegna_catene', 'insegna_catene',$storecheck['insegna_catene']?true:false) ?>
<br />

	<?= form_label('N. banconi di vendita') ?>
	<?= form_input(array('name'=>'num_banconi','size'=>"2",'id'=>"",'value'=>$storecheck['num_banconi']))?>
 
	<?= form_label('N. ingressi') ?>
	<?= form_input(array('name'=>'num_ingressi','size'=>"2",'id'=>"",'value'=>$storecheck['num_ingressi']))?>

</p><p>
	
	<?= form_label('Uso PC') ?>
	<?= form_checkbox('uso_pc', 'uso_pc',$storecheck['uso_pc']?true:false) ?>

	<?= form_label('Sito internet') ?>
	<?= form_checkbox('sito_internet', 'sito_internet',$storecheck['sito_internet']?true:false) ?>

	<?= form_label('Analisi') ?>
	<?= form_checkbox('analisi', 'analisi',$storecheck['analisi']?true:false) ?>


	<?= form_label('Spazio libero servizio') ?>
	<?= form_checkbox('spazio_libero_servizio', 'spazio_libero_servizio',$storecheck['spazio_libero_servizio']?true:false); ?>

	<?= form_label('Aree dedicate') ?>
	<?= form_checkbox('aree_dedicate', 'aree_dedicate',$storecheck['aree_dedicate']?true:false); ?>

	<?= form_label('Orario continuato') ?>
	<?= form_checkbox('orario_continuato', 'orario_continuato',$storecheck['orario_continuato']?true:false); ?>

	<?= form_label('Apertura 24h') ?>
	<?= form_checkbox('apertura_24', 'apertura_24',$storecheck['apertura_24']?true:false); ?>

	<?= form_label('CUP') ?>
	<?= form_checkbox('cup', 'cup',$storecheck['cup']?true:false); ?>
<br />	
	<?= form_label('Localizzazione')?>
	<?= form_dropdown('localizzazione', $localizzazioni,array_search($storecheck['localizzazione'],$localizzazioni));?>
	<?= form_label('Vicino a')?>
	<?= form_dropdown('collocazione', $vicinanze,array_search($storecheck['collocazione'],$vicinanze));?>
	
	<?= form_label('Giorno di chiusura') ?>
	<?= form_dropdown('giorno_chiusura', $giorni_chiusura,array_search($storecheck['giorno_chiusura'],$giorni_chiusura))?>
</p>

<p>	</p>		

<h4>Check eventi richiesti</h4>
	<?= form_label('Intestino, dipende da te') ?>
	<?= form_input(array('name'=>'intestino','value'=>$eventi_richiesti['intestino'],'size'=>10)) ?>

	<?= form_label('Gambe in bamba') ?>
	<?= form_input(array('name'=>'gambe','value'=>$eventi_richiesti['gambe'],'size'=>10)) ?>

	<?= form_label('Questioni di naso') ?>
	<?= form_input(array('name'=>'naso','value'=>$eventi_richiesti['naso'],'size'=>10)) ?>
	<?php echo br();?>
	<?= form_label('Quando lo stomaco brucia') ?>
	<?= form_input(array('name'=>'stomaco','value'=>$eventi_richiesti['stomaco'],'size'=>10)) ?>

	<?= form_label('Proteggiamola fino in fondo') ?>
	<?= form_input(array('name'=>'gola','value'=>$eventi_richiesti['gola'],'size'=>10)) ?>

</p>

<p>
	<h4>Giacenza prodotti per eventi</h4>
	<?= form_label('DULCOFIBRE') ?>
	<?= form_input(array('name'=>'giac_dulcofibre','size'=>"4",'id'=>"",'value'=>$giacenze_prodotti['dulcofibre']))?>

	<?= form_label('ANTISTAX') ?>
	<?= form_input(array('name'=>'giac_antistax','size'=>"4",'id'=>"",'value'=>$giacenze_prodotti['antistax']))?>

	<?= form_label('RINOGUTT AA') ?>
	<?= form_input(array('name'=>'giac_rinogutt','size'=>"4",'id'=>"",'value'=>$giacenze_prodotti['rinogutt']))?>

	<?= form_label('BUSCOPAN AA') ?>
	<?= form_input(array('name'=>'giac_buscopan','size'=>"4",'id'=>"",'value'=>$giacenze_prodotti['buscopan']))?>

	<?= form_label('ZERINOL GOLA') ?>
	<?= form_input(array('name'=>'giac_zerinol','size'=>"4",'id'=>"",'value'=>$giacenze_prodotti['zerinol']))?>

</p>

<h4 onclick="$('#servizi').slideToggle()">Servizi</h4>
<a href="#" onclick="$('#servizi').slideToggle()">mostra/nascondi servizi</a>

<div id="servizi" class="" style="display:none">
<script>show_services('<?php echo $storecheck['reportstorecheck_id'];?>');</script>
</div>
<!--
<p>	
<h4>Check eventi</h4>
	<?= form_label('Intestino, dipende da te') ?>
	<?= form_checkbox('intestino', 'intestino') ?>
	
	<?= form_label('Gambe in bamba') ?>
	<?= form_checkbox('gambe', 'gambe') ?>
	
	<?= form_label('Questioni di naso') ?>
	<?= form_checkbox('naso', 'naso') ?>
	
	<?= form_label('Quando lo stomaco brucia') ?>
	<?= form_checkbox('stomaco', 'stomaco') ?>
	
	<?= form_label('Proteggiamola fino in fondo') ?>
	<?= form_checkbox('gola', 'gola') ?>
	
</p>		
  -->	

<p style="display:inline-table">	<?= form_label('Collaboratore riferimento per servizi')?><br />
	<?= form_textarea(array('name'=>'collab_rif_servizi','rows'=>3,'cols'=>40, 'value'=>$storecheck['collab_rif_servizi']));?>
</p>
<p style="display:inline-table">
<?= form_label('Titolare')?><br />
	<?= form_textarea(array('name'=>'titolare','rows'=>3,'cols'=>40, 'value'=>$storecheck['titolare']))?>
</p>		

<hr />


<h4>Starchannel</h4>
	<?= form_label('Stato dell\'attivit&agrave;') ?>
	<?= form_dropdown('starchannel',$statoStarchannel,array_search($storecheck['starchannel'],$statoStarchannel))?>

<h4>Altre attivit&agrave;</h4>
<p>
	<?= form_label('Starwindow') ?>
	<?= form_checkbox('starwindow', 'starwindow',$storecheck['starwindow']?true:false); ?>

	<?= form_label('Vetrina almanacco') ?>
	<?= form_checkbox('almanacco', 'almanacco',$storecheck['almanacco']?true:false); ?>	
</p>

<p>	<?= form_label('Note')?><br />
	<?= form_textarea(array('name'=>'note','rows'=>5,'cols'=>50, 'value'=>$storecheck['note']));
		//if($mostraPulsanteSalva){	
	?>

</p>

<input type="submit" name="salvaStorecheck" value="salva" class="noprint" />
<?php // }?>
<button onclick="window.print()" class="noprint">stampa</button>
</form>