<?php
class AggiornaAgenti extends CI_Controller{
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
		if(($chiavi=fgetcsv($handle,0,";",'"'))==false){
			echo "errore: lettura nomi dei campi\n";
			return false;
		}
					print_r($chiavi);
		//se non sono presenti tutti i campi necessari, genero un errore ed esco
		if(!$this->verificaCampiEssenziali($chiavi)){
			echo "errore: file csv non corretto\n";
			return false;
		}
		while(($data=fgetcsv($handle,0,";",'"'))!==false){
			
			$data_k=array_combine($chiavi,$data);
			//Ottengo l'oggetto Farmacia, relativo alla riga corrente
			if(!$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($data_k['COD_CLIENTE'])){
				
				//se la farmacia non esiste,lo restituisco all'output e continuo con la prossima riga;
				echo "******************<br>";
				echo "avviso: farmacia ".$data_k['COD_CLIENTE']." non presente nel db<br>";
				echo "******************<br>";
				continue;
			}else{
				$modifiche=$this->aggiornaFarmacia($farmacia,$data_k);
				if($modifiche!=''){
					$farmacia->save();
					echo $farmacia->id." ".$modifiche."<br>";

				}
		}
		unset($farmacia);		
		}
		fclose($handle);
		echo "********************<br>";
		
	}
	
	//verfica che il file contenga tutti i campi necessari all'esecuzione dello script
	private function verificaCampiEssenziali($campi){
		if(!in_array('COD_CLIENTE',$campi)) return false;
		if(!in_array('AGENTE',$campi)) return false;
		if(!in_array('COD_AGENTE',$campi)) return false;
		return true;
	}
	
	//restituisce (true/false,stato) dove true/false indica se sono state fatte modifiche, stato è lo stato farmacia
	private function aggiornaFarmacia($farmacia,$data_k){
		//trasformo il nome advisor in minuscolo con iniziali maiuscole
		$parti=explode(' ',$data_k['AGENTE']);
		foreach($parti as $parte){
			$ele[]=strtoupper(substr($parte,0,1)).strtolower(substr($parte,1));
		}
		$nome_agente=implode(' ',$ele);
		$utenti=Doctrine_Core::getTable('Utente')->findByNome_completo($nome_agente);
		//se non ho trovato il nome completo, allora non è presente l'account
		if(count($utenti)==0){
			$ut=new Utente();
			$ut->nome_completo=$nome_agente;
			$ut->login=$parti[count($parti)-1];
			$ut->password=$parti[0].strlen($parti[0]);
			$ut->save();
			//cerco un eventuale agente esistente con il codice agente della farmacia
			$agenti=Doctrine_Core::getTable('Agente')->findById($data_k['COD_AGENTE']);
			if(count($agenti)==0){
				$ag=new Agente();
				$messaggio="nuovo codice agente ".$data_k['COD_AGENTE']." assegnato a ".$nome_agente."<br>";
			} else {
				$ag=$agenti[0];
				$messaggio="vecchio codice agente ".$data_k['COD_AGENTE']." assegnato a ".$nome_agente."<br>";
			}
			$ag->id=$data_k['COD_AGENTE'];
			$ag->Utente=$ut;
			$ag->save();
			echo $messaggio."<br>";
		}
		if($farmacia->agente_id!=$data_k['COD_AGENTE']){
			$farmacia->agente_id=$data_k['COD_AGENTE'];
			return "modificato agente della farmacia ".$farmacia->id."<br>";
		} else return '';

	}
	
	
}