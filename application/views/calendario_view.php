<div id="core_space" class="">
<script language="Javascript">

function richiestaAjax(url_sorgente,id_destinazione){
	var ajaxRequest = new XMLHttpRequest();
	try{
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
	         } catch (e){
	            return false;
	         }
		}
	}
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
      		var ajaxDisplay = document.getElementById(id_destinazione);
         	ajaxDisplay.innerHTML = ajaxRequest.responseText;
      	}
   }
   ajaxRequest.open("GET", url_sorgente, true);
   ajaxRequest.send(null);
}

function getAttivita(giorno,cod_advisor){
	sorgente="<?php echo site_url('attivitaGiorno_advisor/index')?>/giorno/"+giorno+"/cod_advisor/"+cod_advisor;

	destinazione='elencoAttivita';
	richiestaAjax(sorgente,destinazione);
	document.getElementById(destinazione).style.display='block';
}

function caricaCalendari(quale,corrente,cod_advisor,destinazione){
	var sorgente,destinazione_id;
	if(cod_advisor=='') return;
	if(quale=='tutti' || quale=='prev'){
		if(!destinazione){
			posizione='prev';
			destinazione_id='calendarPrev';
		}else{
			if(destinazione=='next'){
				posizione='next';
				destinazione_id='calendarNext';
			}else if(destinazione=='prev'){
				posizione='prev';
				destinazione_id='calendarPrev';
			}
		}
		sorgente="<?php echo site_url('calendarioSingolo_advisor/index');?>/quale/prev/corrente/"+corrente+"/cod_advisor/"+cod_advisor+"/posizione/"+posizione;
		richiestaAjax(sorgente,destinazione_id);
	}
	if(quale=='tutti' || quale=='curr'){
		sorgente="<?php echo site_url('calendarioSingolo_advisor/index');?>/quale/curr/corrente/"+corrente+"/cod_advisor/"+cod_advisor;
		destinazione_id='calendarCurr';
		richiestaAjax(sorgente,destinazione_id);   
	}
	if(quale=='tutti' || quale=='next'){
		if(!destinazione){
			posizione='next';
			destinazione_id='calendarNext';
		}else{
			if(destinazione=='next'){
				posizione='next';
				destinazione_id='calendarNext';
			}else if(destinazione=='prev'){
				posizione='prev';
				destinazione_id='calendarPrev';
			}
		}
		sorgente= "<?php echo site_url('calendarioSingolo_advisor/index');?>/quale/next/corrente/"+corrente+"/cod_advisor/"+cod_advisor+"/posizione/"+posizione;
		richiestaAjax(sorgente,destinazione_id);    
	}
	if(quale=='tutti'){
		var tabella=document.getElementById('calendarDiv');
		tabella.style.display='block';
	}		
}
 function cambiaAdvisor(mese){
	 if(document.getElementById('frmRcAttAdv_advisor').value=='') return;
	 advisor=document.getElementById('frmRcAttAdv_advisor').value;
	 caricaCalendari('tutti',mese,advisor);
	 document.getElementById('elencoAttivita').innerHTML='';
 }

</script>

<?php 
	// echo "giorno ".$giorno; <- credo servisse per visualizzare il gg dopo cliccato
	$attributi=array(
				'enctype'	=> 'application/x-www-form-urlencoded',
				'id'		=> 'formRicAttAdv',
				'name'		=> 'formRicAttAdv',
				'class'		=> 'formRicAttAdv'
			);
	echo form_open('',$attributi);
	echo form_label('Advisor: ','advisor');
	$attributi='id="frmRcAttAdv_advisor" name="frmRcAttAdv_advisor" onChange="cambiaAdvisor(\''.$mese_corrente.'\')"';
	echo form_dropdown('advisor',$advisors,'',$attributi);	
	//echo $info_utente['nome_completo']; // <- può essere visualizzato solo l'advisor corrente?
	echo form_close();

?>
<div id="calendarDiv" style="display: none;">
		<table>
		<tr>
			<td id="calendarPrev" valign="top"></td>
			<td id="calendarCurr" valign="top"></td>
			<td id="calendarNext" valign="top"></td>
		</tr>
		</table>
</div>
<div id="elencoAttivita" style="display: none;"></div>
<?php if($visualizza_calendari){ ?>
		<script language="Javascript">
			caricaCalendari('tutti','<?php echo $mese_corrente;?>','<?php echo $cod_advisor?>');
		</script>
<?php }
	  if(isset($giorno)){ ?>
		<script language="Javascript">
			getAttivita('<?php echo $giorno?>','<?php echo $cod_advisor?>');
		</script>
<?php }?>
</div>