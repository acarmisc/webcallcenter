<div id="core_space" class="">
<?php 
$flusso_advisor=$farmacia['flusso_lavoro'];
$tipoattivazione="" ;
if(isset($flusso_advisor)){
	switch($flusso_advisor){
		case 'Flusso ordinario':
		$tipoattivazione="Attivazione tramite Agente" ;
			break;
		case 'Contattato da callcenter':
		if($farmacia['stato']=='attivabile')
		{ $tipoattivazione="Da Attivare tramite ADV primo contatto da CallCenter BI" ; }
		if($farmacia['stato']=='attivato da ADV')
		{ $tipoattivazione="Attivata tramite ADV primo contatto da CallCenter BI" ; }
		break;
		case 'Non contattato da callcenter':
		if($farmacia['stato']=='attivabile')
		{ $tipoattivazione="Da Attivare tramite ADV nessun contatto da CallCenter BI" ; }
		if($farmacia['stato']=='attivato da ADV')
		{ $tipoattivazione="Attivata tramite ADV nessun contatto da CallCenter BI" ; }
			break;
		default:
			break;
	}
}


?>
	
	<script>
		function openStoreman(l){
			$('body').append('<div id="dialog" class=""></div>');
			
			
			$.ajax({
			  url: l,
			  success: function(responseText){
			   $('#dialog').html(responseText);
			  }
			});
			
			$(document).ready(function() {
			    $("#dialog").dialog({ buttons: { "Chiudi": function() { $(this).dialog("close"); } },
			    						width: 690 });
			  });
		}
	</script>
	
	<h1>Gestione Cliente &nbsp;<?php echo $tipoattivazione ; ?> 
	<span style="float:right">
		<a href="#" onclick="openStoreman('?c=storeman&farmacia_id=<?= $farmacia['cod_cliente'] ?>')">store</a>
	</span></h1>
	<?php 
			$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formVisualizzaFarmacia',
				'name'		=> 'formVisualizzaFarmacia',
				'class'		=> 'formRicercaFarmaciaClass'/* 'formVisualizzaFarmaciaClass' */
			);
			
		echo form_open('',$attributi)."\n";
		echo "<p>";
		echo form_label('Cod. Cliente: ','cod_cliente');
			$attributi=array(
				'name'		=> 'cod_cliente',
				'size'		=> '10',
				'tabindex'	=> '1',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_cod_cliente',
				'disabled'	=> true,
				'value'		=> $farmacia['cod_cliente']
			);
		echo form_input($attributi);
		echo form_label('Rag. Sociale: ','rag_sociale');
			$attributi=array(
				'name'		=> 'rag_sociale',
				'size'		=> '50',
				'tabindex'	=> '2',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_rag_sociale',
				'disabled'	=> true,
				'value'		=> $farmacia['rag_sociale']
			);
		echo form_input($attributi);
		echo "</p>\n";
		
		echo "<p>";
		echo form_label('Indirizzo: ','indirizzo');
			$attributi=array(
				'name'		=> 'indirizzo',
				'size'		=> '40',
				'tabindex'	=> '3',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_indirizzo',
				'disabled'	=> true,
				'value'		=> $farmacia['indirizzo']
			);
		echo form_input($attributi);
		echo form_label('CAP: ','cap');
			$attributi=array(
				'name'		=> 'cap',
				'size'		=> '6',
				'tabindex'	=> '4',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_cap',
				'disabled'	=> true,
				'value'		=> $farmacia['cap']
			);
		echo form_input($attributi);
		echo form_label('Località: ','localita');
			$attributi=array(
				'name'		=> 'localita',
				'size'		=> '20',
				'tabindex'	=> '5',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_localita',
				'disabled'	=> true,
				'value'		=> $farmacia['localita']
			);
		echo form_input($attributi);
		echo "</p>\n";
	
		echo "<p>";
		echo form_label('Telefono: ','telefono');
			$attributi=array(
				'name'		=> 'telefono',
				'size'		=> '11',
				'tabindex'	=> '6',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_telefono',
				'disabled'	=> true,
				'value'		=> $farmacia['telefono']
			);
		echo form_input($attributi);
		echo form_label('Fax: ','fax');
			$attributi=array(
				'name'		=> 'fax',
				'size'		=> '15',
				'tabindex'	=> '7',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_fax',
				'disabled'	=> true,
				'value'		=> $farmacia['fax']
			);
		echo form_input($attributi);
		echo form_label('E-mail: ','email');
			$attributi=array(
				'name'		=> 'email',
				'size'		=> '37',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_email',
				'disabled'	=> true,
				'value'		=> $farmacia['email']
			);
		echo form_input($attributi);
		echo "</p>\n";
		
		echo "<p>";
		echo form_label('Regione: ','regione');
			$attributi=array(
				'name'		=> 'regione',
				'size'		=> '40',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_regione',
				'disabled'	=> true,
				'value'		=> $farmacia['regione']
			);
		echo form_input($attributi);
		echo form_label('Provincia: ','provincia');
		$attributi=array(
				'name'		=> 'provincia',
				'size'		=> '40',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_provincia',
				'disabled'	=> true,
				'value'		=> $farmacia['provincia']
			);
		echo form_input($attributi);
		echo "</p>\n";
		
		echo "<p>";
		echo form_label('Advisor: ','advisor');
		$attributi=array(
				'name'		=> 'advisor',
				'size'		=> '40',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_advisor',
				'disabled'	=> true,
				'value'		=> $farmacia['advisor']
			);
		echo form_input($attributi);
		
		echo form_label('Agente: ','agente');

		if($farmacia['agente']==null){
			$attributi=array(
					'name'		=> 'agente',
					'size'		=> '40',
					'tabindex'	=> '8',
					'align'		=> 'left',
					'id'		=> 'frmVsfrm_agente',
					'disabled'	=> true,
					'value'		=> 'non assegnato'
				);			
		}else{
			$attributi=array(
					'name'		=> 'agente',
					'size'		=> '40',
					'tabindex'	=> '8',
					'align'		=> 'left',
					'id'		=> 'frmVsfrm_agente',
					'disabled'	=> true,
					'value'		=> $farmacia['agente']
				);			
		}
		echo form_input($attributi);
				echo '</p><p>';
		echo form_label('Stato: ','stato');
		$attributi=array(
				'name'		=> 'stato',
				'size'		=> '40',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmVsfrm_stato',
				'disabled'	=> true,
				'value'		=> $farmacia['stato']
			);
		echo form_input($attributi);
		?>
		</p><p>

			<?= form_label('Nome ISF') ?> <?= form_input(array('id'=>'nome_isf','value'=>$farmacia['isf'], 'disabled'=>true)) ?>
			<?= form_label('Telefono ISF')?> <?= form_input(array('id'=>'telefono_isf','value'=>$farmacia['num_isf'], 'disabled'=>true)) ?>
		<?php
		echo '</p><p>';
			/*echo form_label('Note:','note');
			$attributi=array(
				'id'=>'note',
				'name'=>'nome',
				'value'=>$farmacia['note'],
			);
			echo form_input($attributi);
			if($mostraPulsanteSalvaNote){
				echo form_submit(array('name'=>'salvaNote'),'Salva Note');	
			}
			echo '<p></p>';		*/
		if($flusso_advisor!='Flusso ordinario'){
			echo form_label('Note call center Boehringer:');
			$attributi=array(
				'id'=>'note_boehringer',
				'name'=>'nome_boehringer',
				'value'=>$farmacia['note_boehringer'],
				'disabled'=>true
			);
			echo form_input($attributi);
			echo '<p></p>';				
		}

		?>
	<?= form_label('Intestino, dipende da te') ?>
	<?= form_input(array('name'=>'intestino','value'=>$eventi_richiesti['intestino'],'disabled'=>true,'size'=>10)) ?>

	<?= form_label('Gambe in bamba') ?>
	<?= form_input(array('name'=>'gambe','value'=>$eventi_richiesti['gambe'],'disabled'=>true,'size'=>10)) ?>

	<?= form_label('Questioni di naso') ?>
	<?= form_input(array('name'=>'naso','value'=>$eventi_richiesti['naso'],'disabled'=>true,'size'=>10)) ?>
	<?php echo br();?>
	<?= form_label('Quando lo stomaco brucia') ?>
	<?= form_input(array('name'=>'stomaco','value'=>$eventi_richiesti['stomaco'],'disabled'=>true,'size'=>10)) ?>

	<?= form_label('Proteggiamola fino in fondo') ?>
	<?= form_input(array('name'=>'gola','value'=>$eventi_richiesti['gola'],'disabled'=>true,'size'=>10)) ?>
		
		
	<?php echo "</p>";
		echo form_close();
			echo '<div class="divFormClass">';
	echo '<p>';		

	echo '<span style="float: right;">';
	echo form_open('gestioneCliente',array('name'=>'nuovoAppuntamento'));
	echo form_hidden('nuovo',$farmacia['cod_cliente']);
	if($mostraPulsanteNuovo){
		echo form_submit(array('name'=>'nuovoApp'),'Nuovo Appuntamento');
	}
	/*if($flusso_advisor!='Flusso ordinario' && ($farmacia['stato']='attivabile')){
		echo form_submit(array('name'=>'disinteressa','style'=>'float:left;'),'Non interessato');
	}*/
	echo '<input type="submit" name="storeCheckForm" value="Visualizza storecheck" />';
	echo form_close();
	echo '</span>';
	echo '</p>';
	if(isset($visualizzaStoreCheck) && $visualizzaStoreCheck ){
		$this->load->view('storecheck_form');
	}else{
		if(count($attivita)>0){
			echo"<h1>Attivita programmate</h1>";
			foreach($attivita as $at){
			    echo "<br>" ;
				$data_tabellaAttivita['attivita']=$at;
				$this->load->view('visualizza_attivita_view',$data_tabellaAttivita);
			}
		}
	
		if($mostraNuovo){
	
			echo "<div id='nuovaAttivita'>";
	
				$this->load->view('nuova_attivita',$data_nuovaAttivita);
	
			echo "</div>";
	
		}
	}
		
		//$this->load->view('tabella_attivita_view',$data_tabellaAttivita) ;
			

?>













</div>