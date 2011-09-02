<?php
class ImportaDati3 extends CI_Controller{
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
		if(($chiavi=fgetcsv($handle,0,"\t",'"'))==false){
			echo "errore: lettura nomi dei campi\n";
			return false;
		}
					print_r($chiavi);
		//se non sono presenti tutti i campi necessari, genero un errore ed esco
		if(!$this->verificaCampiEssenziali($chiavi)){
			echo "errore: file csv non corretto\n";
			return false;
		}
		while(($data=fgetcsv($handle,0,"\t",'"'))!==false){
			
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
				$farmacia->save();
				echo $farmacia->id." ".$modifiche."<br>";

				
		}
		unset($farmacia);		
		}
		fclose($handle);
		echo "********************<br>";
		
	}
	
	//verfica che il file contenga tutti i campi necessari all'esecuzione dello script
	private function verificaCampiEssenziali($campi){
		if(!in_array('COD_CLIENTE',$campi)) return false;
		if(!in_array('ADVISOR',$campi)) return false;
		if(!in_array('NOTE OPERATORE',$campi)) return false;
		return true;
	}
	
	//restituisce (true/false,stato) dove true/false indica se sono state fatte modifiche, stato è lo stato farmacia
	private function aggiornaFarmacia($farmacia,$data_k){
		//trasformo il nome advisor in minuscolo con iniziali maiuscole
		$parti=explode(' ',$data_k['ADVISOR']);
		foreach($parti as $parte){
			$ele[]=strtoupper(substr($parte,0,1)).strtolower(substr($parte,1));
		}
		$nome_advisor=implode(' ',$ele);
		$utenti=Doctrine_Core::getTable('Utente')->findByNome_completo($nome_advisor);
		if(count($utenti)==0){
			return "advisor non trovato";
		}
		$utente=$utenti[0];
		$cod_advisor=$utente->Advisor->id;
		$farmacia->advisor_id=$cod_advisor;
		$farmacia->note_boehringer=$data_k['NOTE OPERATORE'];
		$farmacia->flusso_lavoro='Contattato da callcenter';
	}
	
	
}