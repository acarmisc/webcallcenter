<span style="float:right" class="falseButton" onclick="emailCancel()">
	<?= img('img/callcenter/cross-button.png') ?>
</span>

<h2>Mail di conferma appuntamento</h2>
<form id="prepare_email" onsubmit="return false;">

	<p><label>A:</label> <input type="text" name="m_to" /></p>
	<p><label>Oggetto:</label> <input type="text" name="m_subject" value="Conferma appuntamento" /></p>
	<p><label>Nome farmacista:</label> <input type="text" name="m_farmacista" value="" /></p>	
	<p><span class="falseButton" onclick="more_mail(<?= $_GET['aid'] ?>)">Continua &#8250;</span></p>


	<div id="more_mail_space" class="" style="display:none">

	</div>


</form>

<script>

$('input[name=m_to]').val($('input[name=email]').val());
$('input[name=m_bcc]').val($('input[name=adv_email]').val());


</script>