<?php
class AggiornaCodici extends CI_Controller{
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
			if($data_k['COD_SUBENTRATO']!=0){
				//Ottengo l'oggetto Farmacia, relativo alla riga corrente
				if(!$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($data_k['COD_SUBENTRATO'])){		
					//se la farmacia non esiste,lo restituisco all'output e continuo con la prossima riga;
					echo "avviso: farmacia ".$data_k['COD_SUBENTRATO']." non presente nel db<br>";
					unset($farmacia);
					continue;
				}else{
					if($farmacia->id==$data_k['COD_CLIENTE']){
						echo "avviso: farmacia ".$data_k['COD_SUBENTRATO']." già presente nel db con il codice aggiornato<br>";
						unset($farmacia);
					continue;	
					}else{
						if($farmacia_bis=Doctrine_Core::getTable('Farmacia')->findOneById($data_k['COD_CLIENTE'])){
							if($farmacia->indirizzo==$farmacia_bis->indirizzo && $farmacia->localita==$farmacia_bis->localita) continue;
							echo "avviso: il nuovo codice ".$data_k['COD_CLIENTE']." è già presente nel db per un'altra farmacia<br>";
							
							continue;
						}
						unset($farmacia_bis);
						$vecchio=$data_k['COD_SUBENTRATO'];
						$farmacia->id=$data_k['COD_CLIENTE'];
						$farmacia->save();
						echo "Vecchio codice: ".$vecchio."->Nuovo codice: ".$data_k['COD_CLIENTE']."<br>";
						$i++;
					}
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
		if(!in_array('COD_SUBENTRATO',$campi)) return false;
		return true;
	}
	
	
}