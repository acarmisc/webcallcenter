<?php
class ImportaDati extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url'));
	}
	
	public function index(){
		$segmento=$this->uri->segment(3);
		$file=base_url()."data/".$segmento;
		$i=0;
		echo $file.'<br>';
		//se non riesco ad aprire il file, genero un errore ed esco
		if(!$handle=fopen($file,'r')){
			echo "errore: apertura file\n";
			return false;
		}
		//se non riesco ad ottenere l'elenco campi, genero un errore ed esco
		if(($chiavi=fgetcsv($handle,0,";"))==false){
			echo "errore: lettura nomi dei campi\n";
			return false;
		}
		//se non sono presenti tutti i campi necessari, genero un errore ed esco
		if(!$this->verificaCampiEssenziali($chiavi)){
			echo "errore: file csv non corretto\n";
			return false;
		}
		while(($data=fgetcsv($handle,0,';'))!==false){
			$data_k=array_combine($chiavi,$data);
			//Ottengo l'oggetto Farmacia, relativo alla riga corrente
			if(!$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($data_k['COD_CLIENTE'])){
				/*
				//se la farmacia non esiste,lo restituisco all'output e continuo con la prossima riga;
				echo "******************<br>";
				echo "avviso: farmacia ".$data_k['COD_CLIENTE']." non presente nel db<br>";
				echo "******************<br>";
				continue;*/
				//Se la farmacia non esiste, la inserisco
				if($data_k['COD_SUBENTRATO']==0){
					$modifiche = $this->inserisciFarmacia($data_k);
					echo $modifiche."<br>";
					$i++;
				}else{
					$modifiche=$this->effettuaSubentro($data_k['COD_SUBENTRATO'], $data_k['COD_CLIENTE']);
					if($modifiche=="codice subentrato non valido"){
						echo "codice subentrato ".$data_k['COD_SUBENTRATO']." non valido e ignorato<br>";
						$modifiche = $this->inserisciFarmacia($data_k);
						echo $modifiche."<br>";
						$i++;
					}else{
						echo $modifiche." ";
						$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($data_k['COD_CLIENTE']);
						$modifiche=$this->aggiornaRichiesteEventi($farmacia,$data_k);
						if($modifiche!=''){
							$farmacia->save();
							echo $farmacia->id.": ".$modifiche."<br>";
							$i++;
						}
					}
				}
			}else{
				$modifiche=$this->aggiornaRichiesteEventi($farmacia,$data_k);
				if($modifiche!=''){
					$farmacia->save();
					echo $farmacia->id.": ".$modifiche."<br>";
					$i++;
				}
		}
		unset($farmacia);		
		}
		fclose($handle);
		echo "********************<br>";
		echo "Numero farmacie modificate/inserite: ".$i."<br>";
		
	}
	
	//verfica che il file contenga tutti i campi necessari all'esecuzione dello script
	private function verificaCampiEssenziali($campi){
		if(!in_array('COD_CLIENTE',$campi)) return false;
		if(!in_array('GAMBE_FLG',$campi)) return false;
		if(!in_array('NASO_FLG',$campi)) return false;
		if(!in_array('INTESTINO_FLG',$campi)) return false;
		if(!in_array('STOMACO_FLG',$campi)) return false;
		if(!in_array('GOLA_FLG',$campi)) return false;
		return true;
	}
	
	//restituisce (true/false,stato) dove true/false indica se sono state fatte modifiche, stato è lo stato farmacia
	private function aggiornaRichiesteEventi($farmacia,$data_k){
		//verificare che la farmacia non fosse attivata da adv, senza che fosse registrato
		$modificheAgenteRegistrate=false;
		$modifiche='';
		$richieste= $farmacia->RichiesteTipoevento;
		$data_k['NASO_FLG']=str_replace(',','.',$data_k['NASO_FLG']);
		$data_k['GOLA_FLG']=str_replace(',','.',$data_k['GOLA_FLG']);
		$data_k['INTESTINO_FLG']=str_replace(',','.',$data_k['INTESTINO_FLG']);
		$data_k['GAMBE_FLG']=str_replace(',','.',$data_k['GAMBE_FLG']);
		$data_k['STOMACO_FLG']=str_replace(',','.',$data_k['STOMACO_FLG']);
		$sumAgente=0;
		$sumAdvisor=0;
		foreach($richieste as $richiesta){
			if($richiesta->inserito_da_agente){
				$sumAgente+=$richiesta->numero_eventi;
			}else{
				$sumAdvisor+=$richiesta->numero_eventi;
			}
		}
		if($sumAgente==0){
			if($sumAdvisor!=0 && $farmacia->stato!='attivato da ADV'){
				$farmacia->stato='attivato da ADV';
				$modifiche=' modificato stato attivato da ADV ';

			}
		}else if($farmacia->stato!='attivo'){
			$farmacia->stato='attivo';
			$modifiche=' modificato stato attivo';
		}

		//evento di tipo 1, corrispondente a intestino_flg
		foreach($richieste as $richiesta){
			if ($richiesta->tipoevento_id==1 && $richiesta->inserito_da_agente) {
				$r1=$richiesta;
				break;
			}
		}
		//se r1 ha un valore, allora era già stata inserita una richiesta (magari di 0 eventi)
		if(isset($r1)){
			if(! is_numeric($data_k['INTESTINO_FLG'])){
				$numero=0;
			}else{
				$numero=$data_k['INTESTINO_FLG'];
			}
			if($numero!=$r1->numero_eventi){
				$modifiche.=' modificati/aggiunti eventi agente';
				$modificheAgenteRegistrate=true;
				$r1->numero_eventi=$numero;
			}
		}else{
			//se il dato è numerico (non null o stringa vuota) bisogna inserire una richiesta
			if( is_numeric($data_k['INTESTINO_FLG'])){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r=new RichiestaTipoevento();
				$r->tipoevento_id=2;
				$r->numero_eventi=$data_k['INTESTINO_FLG'];
				$r->inserito_da_agente=true;
				$farmacia->RichiesteTipoevento[]=$r;
			}			
		}			
		//evento di tipo 2, corrispondente a gambe_flg
		foreach($richieste as $richiesta){
			if ($richiesta->tipoevento_id==2 && $richiesta->inserito_da_agente) {
				$r2=$richiesta;
				break;
			}
		}
		//se r2 ha un valore, allora era già stata inserita una richiesta (magari di 0 eventi)
		if(isset($r2)){
			if(! is_numeric($data_k['GAMBE_FLG'])){
				$numero=0;
			}else{
				$numero=$data_k['GAMBE_FLG'];
			}
			if($numero!=$r2->numero_eventi){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r2->numero_eventi=$numero;

			}
		}else{
			//se il dato è numerico (non null o stringa vuota) bisogna inserire una richiesta
			if( is_numeric($data_k['GAMBE_FLG'])){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r=new RichiestaTipoevento();
				$r->tipoevento_id=2;
				$r->numero_eventi=$data_k['GAMBE_FLG'];
				$r->inserito_da_agente=true;
				$farmacia->RichiesteTipoevento[]=$r;			
			}		
		}	
		//evento di tipo 3, corrispondente a naso_flg
		foreach($richieste as $richiesta){
			if ($richiesta->tipoevento_id==3 && $richiesta->inserito_da_agente) {
				$r3=$richiesta;
				break;
			}
		}
		//se r3 ha un valore, allora era già stata inserita una richiesta (magari di 0 eventi)
		if(isset($r3)){
			if(! is_numeric($data_k['NASO_FLG'])){
				$numero=0;
			}else{
				$numero=$data_k['NASO_FLG'];
			}
			if($numero!=$r3->numero_eventi){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r3->numero_eventi=$numero;

			}
		}else{
			//se il dato è numerico (non null o stringa vuota) bisogna inserire una richiesta
			if( is_numeric($data_k['NASO_FLG'])){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r=new RichiestaTipoevento();
				$r->tipoevento_id=3;
				$r->numero_eventi=$data_k['NASO_FLG'];
				$r->inserito_da_agente=true;
				$farmacia->RichiesteTipoevento[]=$r;
			}			
		}	
		//evento di tipo 4, corrispondente a stomaco_flg
		foreach($richieste as $richiesta){
			if ($richiesta->tipoevento_id==4 && $richiesta->inserito_da_agente) {
				$r4=$richiesta;
				break;
			}
		}
		//se r4 ha un valore, allora era già stata inserita una richiesta (magari di 0 eventi)
		if(isset($r4)){
			if(! is_numeric($data_k['STOMACO_FLG'])){
				$numero=0;
			}else{
				$numero=$data_k['STOMACO_FLG'];
			}
			if($numero!=$r4->numero_eventi){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r4->numero_eventi=$numero;

			}
		}else{
			//se il dato è numerico (non null o stringa vuota) bisogna inserire una richiesta
			if( is_numeric($data_k['STOMACO_FLG'])){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r=new RichiestaTipoevento();
				$r->tipoevento_id=4;
				$r->numero_eventi=$data_k['STOMACO_FLG'];
				$r->inserito_da_agente=true;
				$farmacia->RichiesteTipoevento[]=$r;
			}
			
		}	
		//evento di tipo 5, corrispondente a gola_flg
		foreach($richieste as $richiesta){
			if ($richiesta->tipoevento_id==5 && $richiesta->inserito_da_agente) {
				$r5=$richiesta;
				break;
			}
		}
		//se r5 ha un valore, allora era già stata inserita una richiesta (magari di 0 eventi)
		if(isset($r5)){
			if(! is_numeric($data_k['GOLA_FLG'])){
				$numero=0;
			}else{
				$numero=$data_k['GOLA_FLG'];
			}
			if($numero!=$r5->numero_eventi){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r5->numero_eventi=$numero;

			}
		}else{
			//se il dato è numerico (non null o stringa vuota) bisogna inserire una richiesta
			if( is_numeric($data_k['GOLA_FLG'])){
				if( $modificheAgenteRegistrate!=true){
					$modifiche.=' modificati/aggiunti eventi agente';
					$modificheAgenteRegistrate=true;
				}
				$r=new RichiestaTipoevento();
				$r->tipoevento_id=5;
				$r->numero_eventi=$data_k['GOLA_FLG'];
				$r->inserito_da_agente=true;
				$farmacia->RichiesteTipoevento[]=$r;
			}			
		}	
		$somma_nuove=0;
		$somma_nuove+=is_numeric($data_k['NASO_FLG'])?$data_k['NASO_FLG']:0;
		$somma_nuove+=is_numeric($data_k['GOLA_FLG'])?$data_k['GOLA_FLG']:0;		
		$somma_nuove+=is_numeric($data_k['GAMBE_FLG'])?$data_k['GAMBE_FLG']:0;		
		$somma_nuove+=is_numeric($data_k['INTESTINO_FLG'])?$data_k['INTESTINO_FLG']:0;		
		$somma_nuove+=is_numeric($data_k['STOMACO_FLG'])?$data_k['STOMACO_FLG']:0;		
		if($somma_nuove!=$sumAgente && $farmacia->stato='attivabile'){
			$farmacia->stato='attivo';
			$modifiche.=' modificato stato attivo ';
		}
		return $modifiche;
	}
	
	private function inserisciFarmacia($dati){
		$esito='cliente '.$dati['COD_CLIENTE'];
		$farmacia= new Farmacia();
		$farmacia->id = $dati['COD_CLIENTE'];
		$farmacia->denominazione = $dati['DES_CLIENTE'];
		$farmacia->indirizzo = $dati['INDIRIZZO'];
		$farmacia->cap = $dati['CAP'];
		$farmacia->localita = $dati['CITY'];
		$farmacia->advisor_id='ADV1';
		$farmacia->stato='attivabile';
		$farmacia->numtel = $dati['TELEFONO'];
		$farmacia->numfax = $dati['FAX'];
		$farmacia->email = $dati['EMAIL'];
		while(strlen($dati['COD_MICROBRICK'])<7) $dati['COD_MICROBRICK']='0'.$dati['COD_MICROBRICK'];
		if($ag=Doctrine_Core::getTable('Agente')->findOneById($dati['COD_AGENTE'])){
			$farmacia->agente_id = $dati['COD_AGENTE'];
		}else{
			$esito.=" non inserito: codice agente errato!";
			return $esito;
		}
		if($ag=Doctrine_Core::getTable('Microbrick')->findOneById($dati['COD_MICROBRICK'])){
			$farmacia->microbrick_id = $dati['COD_MICROBRICK'];
		}else{
			$esito.=" non inserito: codice microbrick errato!";
			return $esito;
		}
		if($ag=Doctrine_Core::getTable('Provincia')->findOneById($dati['PROVINCIA'])){
			$farmacia->provincia_id = $dati['PROVINCIA'];
		}else{
			$esito.=" non inserito: codice provincia errato!";
			return $esito;
		}
		$li=Doctrine_Core::getTable('Linea')->findByDescrizione($dati['LINEA_CLIENTE']);
		if(count($li)==1){
			$farmacia->linea_id = $li[0]->id;
		}else{
			$esito.=" non inserito: codice linea errato!";
			return $esito;
		}		
		$this->aggiornaRichiesteEventi($farmacia,$dati);
		$farmacia->save();
		return $esito." inserito!";
	}
	private function effettuaSubentro($vecchioCodice,$nuovoCodice){
		if($vecchio=Doctrine_Core::getTable('Farmacia')->findOneById($vecchioCodice)){
			$vecchio->id=$nuovoCodice;
			$vecchio->save();
			return "vecchio codice: ".$vecchio->id." subentrato come ".$nuovoCodice;
		}else{
			unset($vecchio);
			return "codice subentrato non valido";
		}
		
	}
}
