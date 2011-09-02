
<div id="core_space" class="">
	
	<h1>Profilo Personale</h1>
	<p>Nome e Cognome: <?php echo $utente['nome_completo']?$utente['nome_completo']:''?></p>
	<p>Nome utente: <?php echo $utente['login']?$utente['login']:''?></p>
	<?php 
	$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formUserProfile',
				'name'		=> 'formUserProfile',
				'class'		=> 'formRicercaFarmaciaClass'
			);
		echo form_open('userProfile',$attributi)."\n";
	?>
	<p><?php echo form_label('Indirizzo E-mail: ','email').form_input('email',$utente['email']?$utente['email']:'');?></p>
	<p><?php echo form_label('Numero di telefono: ','numtel').form_input('numtel',$utente['numtel']?$utente['numtel']:'');?></p>
	<p><?php echo form_label('Numero di cellulare: ','numcel').form_input('numcel',$utente['numcel']?$utente['numcel']:'');?></p>
	<p><?php echo form_submit('inviaDati_utente','Aggiorna')?></p>
	<?php echo form_close();?>
	<?php 
	if($stato_profilo!=''){
		echo "<p>".$stato_profilo."</p>\n";
	}
	?>
	<h1>Cambio password.</h1>
	<?php 
	$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formUserPassword',
				'name'		=> 'formUserPassword',
				'class'		=> 'formRicercaFarmaciaClass'
			);
		echo form_open('userProfile',$attributi)."\n";
	?>
	<p><?php echo form_label('Vecchia password: ','old_password').form_password('old_password',set_value('old_password',''));?></p>
	<p><?php echo form_label('Nuova password: ','new_password').form_password('new_password',set_value('new_password'));?></p>
	<p><?php echo form_label('Controllo password: ','control_password').form_password('control_password','');?></p>
	<p><?php echo form_submit('inviaPassword','Aggiorna')?></p>
	<?php echo form_close();?>
	<?php 
	if($stato_password!=''){
		echo "<p>".$stato_password."</p>\n";
	}
	?>
	
</div>