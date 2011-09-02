<form class="giacenza-form" id="giacenza-form" method="post" action="" onsubmit="return false;">


	<?= form_hidden('farmacia_id',$this->session->userdata('current_farmacia')) ?>

	
	<?= form_hidden('data_insert',time()) ?>
	<p><label>Prodotto</label>
		<select name="prodotto_id">
				<option value="null">Scegli un prodotto...</option>
			<?php
				$query = $this->db->get('prodotto');
				foreach($query->result() as $r){ ?>
				<option value="<?= $r->id ?>"><?= $r->nome ?></option>
			<?php	}
			?>
			</select>
			
			<label>Mese</label>
			<select name="mese_id" onchange="">
				<option value="null">Scegli un mese...</option>
			<?php
				$query = $this->db->get('store_mesi');
				foreach($query->result() as $r){ ?>
				<option value="<?= $r->id ?>"><?= $r->nome_mese ?></option>
			<?php	}
			?>
			</select>
			<label>Quantit&agrave;</label>
			<span id="createIn">
				<input type="text" size="4" name="quantita" />
			</span>
			<button onclick="saveGiacenza()">salva</button>
	</p>
	
</form>