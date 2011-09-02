<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	
	<title>Pharma3D</title>
	<link rel="stylesheet" media="screen" href="<?= base_url()?>css/default.css" />
	<link rel="stylesheet" media="screen" href="<?= base_url()?>css/callcenter.css" />
	<link rel="stylesheet" media="screen" href="<?php echo base_url().'css/cupertino/jquery-ui-1.7.2.custom.css'?>" />
	<script src="<?= base_url() ?>js/jquery.js"></script>
	<script src="<?= base_url() ?>js/jquery-ui.js"></script>	
	<script src="<?= base_url() ?>js/callcenter.js"></script>	
</head>
<body>

	<div id="header" class="header">
		<div id="logo" class="">
			<img src="<?php echo base_url();?>/img/logo.png" />
		</div>
		<div id="user_logged" class=""> 
			<?php if ($this->session->userdata('uid') == '') echo '###'; echo $this->session->userdata('uname') ?>
			<br /><?= date('d-m-Y H:i',time())?>
		</div>
	</div>
	
	<?php if ($this->session->userdata('uid') == ''){ echo ''; }else{ ?>
<div id="" class="navbar">
		<ul>
			<li><a href="?c=callcenter">Chiamate da fare</a></li>
			<li><a href="?c=callcenter&m=history">Storico chiamate</a></li>
			<li><a href="?c=callcenter&m=logout">Esci</a></li>
		</ul>
	</div>
	<?php } ?>
	
	<div id="callcenter_body" class="">