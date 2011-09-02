<h3>Questionario in chiamata</h3>

<form id="questionario" onsubmit="return false;">
<p><label>Farmacia:</label> <?= $chiamata[0]->denominazione ?></p>



<p align="center">
	<button onclick="cancelQ()">Annulla</button>
	<button onclick="completeQ()">Salva</button>
</p>
</form>