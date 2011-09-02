<!-- lista tipi di attività dal db al campo... $tipi_attivita?!? -->

<script src="<?= base_url() ?>js/jquery.js"></script>
<script src="<?= base_url() ?>js/jquery-ui.js"></script> 

<form name="storecheck_form" class="formRicercaFarmaciaClass" action="#" method="POST">
<?php echo form_hidden('farmacia_id',$farmacia_id)?>
<?= form_label('giorno') ?>
<?= form_input(array('name'=>'giorno','size'=>10,'id'=>'giorno'))?>
<script>$('#giorno').datepicker({ dateFormat: 'dd/mm/yy' });</script>

<?= form_label('ora inizio') ?>
<?= form_input(array('name'=>'ora_inizio','size'=>5))?>

<?php echo form_label('stato');?>
<?php echo form_dropdown('stato',array('aperta'=>'aperta','chiusa'=>'chiusa'),'aperta');?>
<br/>
<?= form_label('tipo attivit&agrave;')?>
<?= form_dropdown('tipoattivita_id', $tipi_attivita)?>
<?= form_label('tipo evento')?>
<?= form_dropdown('tipoevento_id', $tipi_evento)?>


<?= form_label('data chiusura') ?>
<?= form_input(array('name'=>'data_chiusura','size'=>10,'id'=>'data_chiusura'))?>
<script>$('#data_chiusura').datepicker();</script>

<?php echo form_submit(array('name'=>'salvaNuovoApp','value'=>'salva'));?>
</form>
