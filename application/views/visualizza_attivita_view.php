<!-- lista tipi di attività dal db al campo... $tipi_attivita?!? -->
<script src="js/ajaxsbmt.js" type="text/javascript"></script>

<script type="text/javascript">
	function conferma(){
		var risposta=confirm("Vuoi veramente eliminare l'attività?");
		if(risposta) {return true;}
		else {return false;}
	}

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
	
	function reportAttivita(idAttivita){
		for(var i in document.getElementsByTagName('div')){
			if(typeof i.id !='undefined') alert(i.id.substring(0,7));
			if(typeof i.id !='undefined' && i.id.substring(0,7)=='report_') i.style.display="none";
		}		
		var divAttivo=document.getElementById('report_'+idAttivita);
		richiestaAjax('index.php/reportattivita/index/farma/<?php echo $attivita['farmacia_id'];?>/idAttivita/'+idAttivita,divAttivo.id);
		divAttivo.style.display="block";
		
	}

	function richiestaAjaxPost(url_sorgente,id_destinazione,richiestaPost) {
		var xmlHttpReq = false;
		var self = this;
		if (window.XMLHttpRequest) {
			self.xmlHttpReq = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/*try{
			self.xmlHttpReq = new XMLHttpRequest();
		} catch (e){
			try{
				self.xmlHttpReq = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		         } catch (e){
		            return false;
		         }
			}
		}*/
		self.xmlHttpReq.open('POST', url_sorgente, true);
		self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		self.xmlHttpReq.onreadystatechange = function() {
			if (self.xmlHttpReq.readyState == 4) {
				document.getElementById(id_destinazione).innerHTML = self.xmlHttpReq.responseText;
			}
		}
		self.xmlHttpReq.send(richiestaPost);
	}
	function postString(idAttivita){
		var str='';
		var e;

		formReport=document.forms['reportattivita_form_'+idAttivita];
		for(e=0;e< formReport.elements.length;e++){
			if(formReport.elements[e].type=='checkbox'){
				str=str+formReport.elements[e].name+'='+formReport.elements[e].checked+'&';
			}else{
				str=str+formReport.elements[e].name+'='+formReport.elements[e].value+'&';
			}
		}
		return str;
	}

	function salvaReport(idAttivita){
		richiesta=postString(idAttivita);
		richiestaAjaxPost("<?php echo site_url('reportattivita/save')?>",'report_'+idAttivita,richiesta);

		return false;	
	}
		
</script>


<form name="storecheck_form" class="formRicercaFarmaciaClass" action="#" method="POST">
<?php echo form_hidden('farmacia_id',$attivita['farmacia_id']);
	  echo form_hidden('attivita_id',$attivita['attivita_id']);
?>
<?= form_label('giorno') ?>
<?= form_input(array('name'=>'giorno','size'=>10,'id'=>'giorno_'.$attivita['attivita_id'],'value'=>$attivita['giorno']))?>
<!--<script>$('#giorno_<?= $attivita['attivita_id'] ?>').datepicker({ dateFormat: 'dd/mm/yy' });</script>-->

<?= form_label('ora inizio') ?>
<?= form_input(array('name'=>'ora_inizio','size'=>5, 'value'=>$attivita['ora_inizio']))?>

<?php echo form_label('stato');?>
<?php echo form_dropdown('stato',array('aperta'=>'aperta','chiusa'=>'chiusa'),$attivita['stato']);?>

<br/>
<?= form_label('tipo attivit&agrave;')?>
<?= form_dropdown('tipoattivita_id', $tipi_attivita,$attivita['tipoattivita_id'])?>
<?= form_label('tipo evento')?>
<?= form_dropdown('tipoevento_id', $tipi_evento,$attivita['tipoevento_id'])?>


<?= form_label('data chiusura') ?>
<?= form_input(array('name'=>'data_chiusura','size'=>10,'id'=>'data_chiusura_'.$attivita['attivita_id'],'value'=>$attivita['data_chiusura']))?>
<!--<script>$('#data_chiusura_<?= $attivita['attivita_id'] ?>').datepicker({ dateFormat: 'dd/mm/yy' });</script>-->
<br />
<?php 
if($permesso_modifiche){
 echo	'<input type="submit" name="btModificaAttivita" value="modifica" />';
}
if($permesso_cancella){
 echo form_submit('btEliminaAttivita',"elimina",'onClick=\'return conferma();\'');
}
if($attivita['ha_report']){
?>
	<span><a href="#" onClick="reportAttivita('<?php echo $attivita['attivita_id'];?>')">Inserisci/modifica report dell'attività</a></span>
<?php }?>
</form>

<div id="report_<?php echo $attivita['attivita_id']?>" name="divReport" style="display: none;">
</div>             


<script>
    $(document).ready(function() {
				$('#giorno_<?= $attivita['attivita_id'] ?>').datepicker({ dateFormat: 'dd/mm/yy' });  
				$('#data_chiusura_<?= $attivita['attivita_id'] ?>').datepicker({ dateFormat: 'dd/mm/yy' });
	});


</script>