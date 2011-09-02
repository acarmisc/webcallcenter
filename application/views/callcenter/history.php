<h1>Chiamate effettuate</h1>

<p>Ultime 100 chiamate effettuate da <b><?= $this->session->userdata('uname') ?></b>.</p>

<table class="tableRisRicClass" width="960">
	<tr>
		<th width="30"></th>
		<th>ID</th>
		<th>inizio chiamata</th>
		<th>fine chiamata</th>	
		<th>durata</th>	
		<th>esito</th>
		<th>note</th>
	</tr>
	
<?php foreach($calls as $r){ ?>

<?php
	$stat = 'new';
	
	$stat = $r->esito;
	
	$dur = $r->end_timestamp-$r->start_timestamp;

	if($dur > 240) 
		$dur = ($dur/60)." min.";
	else
		$dur = $dur." sec.";
	
?>

	<tr class="<?= $stat ?>">
		<td><?= img('img/callcenter/'.$stat.'.png') ?></td>
		<td><a href="?c=callcenter&m=reopen_call&disabled=true&id=<?= $r->id ?>"><?= $r->id ?></a></td>
		<td><?= date('d-m-Y H:i:s', $r->start_timestamp) ?></td>
		<td><?= date('d-m-Y H:i:s', $r->end_timestamp) ?></td>
		<td><?= $dur ?></td>
		<td><?= $r->esito ?></td>
		<td><?= $r->note_chiamata ?></td>
	</tr>
<?php } ?>

</table>

<p><a href="?c=callcenter">&#8249; indietro</a>	</p>