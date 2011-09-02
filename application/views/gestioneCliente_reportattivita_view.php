<script language="Javascript">



</script>
<form name="reportattivita_form_<?php echo $report['attivita_id'];?>" class="formRicercaFarmaciaClass" action='' onSubmit=" return salvaReport('<?php echo $report['attivita_id'];?>');">
<?php 
switch($tipoattivita_id){
	//pre-evento

	case 2:
		echo form_hidden('farmacia_id',$report['farmacia_id']);
		echo form_hidden('attivita_id',$report['attivita_id']);
		echo form_label('Leaflet educazionali','leaf_edu');
		echo form_checkbox('leaf_edu','leaf_edu',$report['leaf_edu']?true:false);
		echo form_label('Leaflet di prodotto','leaf_prodotto');
		echo form_checkbox('leaf_prodotto','leaf_prodotto',$report['leaf_prodotto']?true:false);
		echo form_label('Locandine di campagna','locandine');
		echo form_checkbox('locandine','locandine',$report['locandine']?true:false);
		echo form_label('Espositore di prodotto','espositore');
		echo form_checkbox('espositore','espositore',$report['espositore']?true:false);
		echo form_label('Totem','totem');
		echo form_checkbox('totem','totem',$report['totem']?true:false);
		echo form_label('Ticket','ticket');
		echo form_checkbox('ticket','flag',$report['ticket']?true:false);
		echo form_label('Nr. appuntamenti raccolti:','appuntamenti');
		echo form_input(array('name'=>'appuntamenti','size'=>"4",'id'=>"",'value'=>$report['appuntamenti']));
		echo form_label('Partecipazione farmacista','partecipazione_farmacista');
		echo form_dropdown('partecipazione_farmacista',$gradi_partecipazione,array_search($report['partecipazione_farmacista'],$gradi_partecipazione));
		echo "<br>";
		echo form_label('note','note');
		echo form_textarea(array('name'=>'note','rows'=>5,'cols'=>50, 'value'=>$report['note']));		
		break;
	//evento
	case 3:
		echo form_hidden('farmacia_id',$report['farmacia_id']);
		echo form_hidden('attivita_id',$report['attivita_id']);
		echo form_label('Effettuato pre-evento: ','preevento');
		echo form_checkbox('preevento','preevento',$report['preevento']);
		echo "<br>";		 
		echo form_label('Nr. contatti con appuntamento','contatti_appuntamento');
		echo form_input(array('name'=>'contatti_appuntamento','size'=>"4",'id'=>"",'value'=>$report['contatti_appuntamento']));
		echo form_label('Nr. contatti senza appuntamento','contatti_noappuntamento');
		echo form_input(array('name'=>'contatti_noappuntamento','size'=>"4",'id'=>"",'value'=>$report['contatti_noappuntamento']));	
		echo form_label('Sell out','sellout');
		echo form_input(array('name'=>'sellout','size'=>"4",'id'=>"",'value'=>$report['sellout']));
		echo "<br>";	
		echo form_label('soddisfazione farmacista','soddisfazione_farmacista');
		echo form_dropdown('soddisfazione_farmacista',$gradi_soddisfazione,array_search($report['soddisfazione_farmacista'],$gradi_soddisfazione));
		echo form_label('Store magazzino fine giornata','store_magazzino');
		echo form_input(array('name'=>'store_magazzino','size'=>"4",'id'=>"",'value'=>$report['store_magazzino']));			
		echo "<br>";
		echo form_label('note','note');
		echo form_textarea(array('name'=>'note','rows'=>5,'cols'=>50, 'value'=>$report['note']));
		echo "<br>";
		echo "Questionari di autovalutazione:<br>";
		switch($report['tipoevento_id']){
			case 1:
				$etichetta[0]="da 3 a 18: ";
				$etichetta[1]="da 19 a 29: ";
				$etichetta[2]="da 30 in su: ";
				break;
			case 2:
				$etichetta[0]="da 4 a 17: ";
				$etichetta[1]="da 18 a 28: ";
				$etichetta[2]="da 29 a 39: ";
				$etichetta[3]="da 40 in su: ";
				break;
			case 3:
				$etichetta[0]="da 0 a 10: ";
				$etichetta[1]="da 11 a 18: ";
				$etichetta[2]="da 19 in su: ";
				break;
		}
		if($report['tipoevento_id']<4){
			foreach($report['dati_questionari'] as $chiave=>$valore){
				echo form_label($etichetta[$chiave],'dati_questionari['.$chiave.']');
				echo form_input(array('name'=>'dati_questionari['.$chiave.']','size'=>"4",'id'=>"",'value'=>$valore));	
			}
		}
		echo "<br>";
		break;
}

?>
<input type="submit" name="salva_reportattivita" value="salva" >
</form>
