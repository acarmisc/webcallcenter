<div id="core_space" class="">
	<h1>Ricerca Clienti</h1>
	<?php 
			$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formRicercaFarmacia',
				'name'		=> 'formRicercaFarmacia',
				'class'		=> 'formRicercaFarmaciaClass'
			);
		echo form_open($pagina_risultati,$attributi)."\n";
		echo "<p>";
		echo form_label('Cod. Cliente: ','cod_cliente');
			$attributi=array(
				'name'		=> 'cod_cliente',
				'size'		=> '10',
				'tabindex'	=> '1',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_cod_cliente',
				'value'		=> set_value('cod_cliente','')
			);
		echo form_input($attributi);
		echo form_label('Rag. Sociale: ','rag_sociale');
			$attributi=array(
				'name'		=> 'rag_sociale',
				'size'		=> '50',
				'tabindex'	=> '2',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_rag_sociale',
				'value'		=> set_value('rag_sociale','')
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
				'id'		=> 'frmRcfrm_indirizzo',
				'value'		=> set_value('indirizzo','')
			);
		echo form_input($attributi);
		echo form_label('CAP: ','cap');
			$attributi=array(
				'name'		=> 'cap',
				'size'		=> '6',
				'tabindex'	=> '4',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_cap',
				'value'		=> set_value('cap','')
			);
		echo form_input($attributi);
		echo form_label('Località: ','localita');
			$attributi=array(
				'name'		=> 'localita',
				'size'		=> '20',
				'tabindex'	=> '5',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_localita',
				'value'		=> set_value('localita','')
			);
		echo form_input($attributi);
		echo "</p>\n";
	
		echo "<p>";
		echo form_label('Telefono: ','telefono');
			$attributi=array(
				'name'		=> 'telefono',
				'size'		=> '12',
				'tabindex'	=> '6',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_telefono',
				'value'		=> set_value('telefono','')
			);
		echo form_input($attributi);
		echo form_label('Fax: ','fax');
			$attributi=array(
				'name'		=> 'fax',
				'size'		=> '12',
				'tabindex'	=> '7',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_fax',
				'value'		=> set_value('fax','')
			);
		echo form_input($attributi);
		echo form_label('E-mail: ','email');
			$attributi=array(
				'name'		=> 'email',
				'size'		=> '40',
				'tabindex'	=> '8',
				'align'		=> 'left',
				'id'		=> 'frmRcfrm_email',
				'value'		=> set_value('email','')
			);
		echo form_input($attributi);
		echo "</p>\n";
		
		echo "<p>";
		echo '<span id="selectRegione">';
		echo form_label('Regione: ','regione');
		$attributi='id="frmRcfrm_regione" name="regione"';
		echo form_dropdown('regione',$regioni,'',$attributi);
		echo '</span>';
		echo '<span id="selectProvincia">';
		echo form_label('Provincia: ','provincia');
		$attributi='id="frmRcfrm_provincia" name="provincia"';
		echo form_dropdown('provincia',$province,'',$attributi);
		echo '</span>';
		echo '<span id="selectAdvisor">';
		echo form_label('Advisor: ','advisor');
		$attributi='id="frmRcfrm_advisor" name="advisor"';
		echo form_dropdown('advisor',$advisors,'',$attributi);
		echo '</span>';
		echo '<span id="selectAgente">';
		echo form_label('Agente: ','agente');
		$attributi='id="frmRcfrm_agente" name="agente"';
		echo form_dropdown('agente',$agenti,'',$attributi);
		echo '</span>';
		echo "</p>";
		
		echo "<p>";
		echo form_label('Stato del cliente: ','stato');
		$attributi='name="stato" id="frmRcfrm_stato"';
		$stati=array(
			0					=> '',
			'attivabile'		=> 'Attivabile',
			'attivo'			=> 'Attivo',
			'non interessato'	=> 'Non interessato',
			'attivato da ADV'	=> 'Attivato da ADV'
		);
		echo form_dropdown('stato',$stati,'',$attributi);
		echo form_label('Flusso di attivazione: ','flusso_lavoro');
		$attributi='id="frmRcfrm_flusso_lavoro" name="flusso_lavoro"';
		echo form_dropdown('flusso_lavoro',$flussi_lavoro,'',$attributi);
		echo "</p>";
		
		echo '<p align="center">';
		$attributi=array(
			'name'	=> 'submit',
			'id'	=> 'formRicercaFarmaciaSubmit'
		);
		echo form_submit($attributi,'Cerca');
		$attributi=array(
			'name'	=> 'reset',
			'id'	=> 'formRicercaFarmaciaReset'
		);
		echo form_reset($attributi,'Azzera criteri');
		echo '</p>';
		
		
		
		
		
	?>
</div>