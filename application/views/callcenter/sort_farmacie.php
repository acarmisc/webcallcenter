<?php
$this->db->select('farmacia.denominazione, calling.*');
$this->db->join('farmacia','farmacia.id = calling.farmacia_id');

$query_open = $this->db->get_where('calling', array('operatore'=>$this->session->userdata('uid'), 'calling.stato'=>1));
$n = $query_open->num_rows();
if($n > 0){ ?>
	<div id="" class="callcenter-alert">
		Ci sono <?= $n ?> chiamate aperte!
<?php	foreach($query_open->result() as $r){ ?>
	<li><?= img('img/callcenter/lock.png') ?> <a href="?c=callcenter&m=reopen_call&id=<?= $r->id ?>"> 
	<?= $r->denominazione ?></a> iniziata alle <?= date('d-m-Y H:i:s',$r->start_timestamp) ?></li>	
<?php	} ?>
</div>
<?php }	?>


<table class="tableRisRicClass" width="960">
	<tr>
		<th width="30"></th>
		<th width="90">giorno</th>
		<th width="50">stato</th>
		<th>farmacia</th>		
		<th width="180">localit&agrave;</th>
		<th width="90">attivit&agrave;</th>
		<th width="90"></th>
	</tr>
</table>
<div id="" class="callcenter-callslist">
<table width="960" class="tableRisRicClass">
<?php foreach($query->result() as $r){ ?>

<?php
	$stat = 'new';
	$this->db->order_by('id DESC');
	$this->db->limit(1);
	$qrcalls = $this->db->get_where('calling',array('farmacia_id'=>$r->id,'attivita_id'=>$r->attid));
	foreach($qrcalls->result() as $rc){
		$stat = $rc->esito;
	}
?>

	<tr class="<?= $stat ?>">
		<td width="30"><?= img('img/callcenter/'.$stat.'.png') ?></td>
		<td width="90"><?= $r->giorno ?></td>
		<td width="50"><?= $r->attstat ?></td>
		<td><?= $r->denominazione ?></td>
		<td width="180"><?= $r->localita ?> (<?= $r->provincia_id ?>)</td>
		<td width="90"><?= $r->nome ?></td>
		<td width="90" id="tools-<?= $r->id ?>">
			<?php
				$s = 0;
				$query = $this->db->get_where('calling',array('farmacia_id'=>$r->id,'attivita_id'=>$r->attid));
				foreach($query->result() as $r1){
					$s += $r1->stato;
					if($r1->stato == 1){ $cid = $r1->id; }
				}
				if($s < 1){
			?>
				
				
				<a href="?c=callcenter&m=open_call&id=<?= $r->attid ?>">
				
				
				<?= img('img/callcenter/telephone--plus.png') ?></a>
				
				
				<span style="display:inline-table; width:30px"></span>
				
			
				<a href="?c=callcenter&m=last_calls&fid=<?= $r->id ?>&aid=<?= $r->attid ?>"><?= img('img/callcenter/blue-document-task.png') ?></a>
			
			<?php	}else{ ?>
				
				<?= img('img/callcenter/lock.png') ?> 
					<?php if($this->session->userdata('uid') == $r1->operatore){?>
					<a href="?c=callcenter&m=reopen_call&id=<?= $cid ?>">in corso...</a>
					<?php }else{ ?>
						in corso...
					<?php } ?>
			<?php	}	?>
		</td>
	</tr>
<?php } ?>
</table>
</div>
<p class="cc-legenda" style="float:left">
<?= img('img/callcenter/new.png') ?> da chiamare 
<?= img('img/callcenter/confermato.png') ?> confermato 
<?= img('img/callcenter/non trovato.png') ?> non trovato 
<?= img('img/callcenter/rimandato.png') ?> rimandato 
</p>

<p class="cc-legenda" style="float:right">
<?= img('img/callcenter/telephone--plus.png') ?> effettua chiamata 
<?= img('img/callcenter/blue-document-task.png') ?> lista chiamate 
</p>