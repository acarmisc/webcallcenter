<link rel="stylesheet" media="screen" href="<?php echo base_url().'css/cupertino/jquery-ui-1.7.2.custom.css'?>" />
	<script src="<?= base_url() ?>js/jquery.js"></script>
	<script src="<?= base_url() ?>js/jquery-ui.js"></script>	

<script>
	function saveGiacenza(){
	
		$.ajax({
		type: 'POST',
		url: '?c=storeman&m=saveG',
		data: $('#giacenza-form').serialize(),  
		success: function(responseText){
			$('#ans').html(responseText);		
		}
	});
		
	}
</script>



 	<link rel="stylesheet" media="screen" href="<?= base_url()?>css/default.css" /> 
	<link rel="stylesheet" media="screen" href="<?= base_url()?>css/magazzino.css" />


<h3>Gestione giacenze</h3>

<?= $this->load->view('storeman/crea'); ?>


<div id="ans" class="">
	<?= $this->load->view('storeman/panoramica'); ?>
</div>


