<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<?php if(!isset($head_alt)){?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php }else echo $head_alt;?>
	<title>Pharma3D-<?php echo $title;?></title>
	<link rel="stylesheet" media="screen" href="<?php echo base_url().$stylesheet;?>" />
	<link rel="stylesheet" media="screen" href="<?php echo base_url().'css/cupertino/jquery-ui-1.7.2.custom.css'?>" />
	<script src="<?= base_url() ?>js/jquery.js"></script>
	<script src="<?= base_url() ?>js/jquery-ui.js"></script>	
	<script src="<?= base_url() ?>js/pharma3d.js"></script>	
</head>
<body>
	<?php if($this->uri->segment(1) == ''){ ?>
		<div id="man" style="position:absolute; bottom:0px; left:10px; z-index:2;top:220px">
			<?= img('img/man.png')?>
		</div>
	
	<?php	}	?>

	<div id="header" class="header">
		<div id="logo" class="">
			<img src="<?php echo base_url();?>/img/logo.png" />
		</div>
		<div id="user_logged" class=""> 
			<?php
				echo "Benvenuto";
				if($nome_completo!=''){
					echo ", <b>".$info_utente['nome_completo']."</b>!";
				}else{
					echo "!";
				}
			?>
			<br /><?= date('d-m-Y H:i',time())?>
		</div>
	</div>
	
	<?php
	if($mostra_form_login){
			$dato['login_fallito']=$login_fallito;
			$this->load->view('login_form.php',$dato);
		}else{
				$this->load->view('toolbar',$data_toolbar);
		}

	?>
	
	<div id="main_body" class="">
		<?php 
		/*
		spostato per necessaria indipendenza da "main_body"
if($mostra_form_login){
			$dato['login_fallito']=$login_fallito;
			$this->load->view('login_form.php',$dato);
		}else{
				$this->load->view('toolbar',$data_toolbar);
		}
		
*/
		?>	
			
			<?php 
				if(!isset($data_mainbox)) $data_mainbox='';
				$this->load->view($main_box,$data_mainbox);
			
			?>
			
			
		</div>
		
		<div id="footer" class="">
			Applicazione realizzata da CSO Pharmitalia
		</div>


</body>
</html>
