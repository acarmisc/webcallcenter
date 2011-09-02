<?php
require_once 'sicurezza.php';
class attivitaGiorno_advisor extends CI_Controller {
	private $risposta_vuota=false;
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date','cookie'));
		$this->load->library(array('session','table','calendar'));
		//se l'utente non risulta loggato, viene fornita una risposta vuota
		if(!Sicurezza::verificaUtente(false)){
			$this->risposta_vuota=true;
		}
		$this->data['info_utente']=Utente_Corrente::informazioni();
	}
	
	public function index(){

		if($this->risposta_vuota){
			echo '<p></p>';
			return;
		}
		$richiesta=$this->uri->uri_to_assoc(3);
		//la richiesta deve avere data e codice_advisor
		if(!(isset($richiesta['giorno']) && isset($richiesta['cod_advisor']))){
			echo '<p></p>';
			return;
		}

		//la data ha formato yyyymmX dove X è il giorno indicato con una o due cifre.
		$anno_richiesto=substr($richiesta['giorno'],0,4);
		$mese_richiesto=substr($richiesta['giorno'],4,2);
		$giorno_richiesto=substr($richiesta['giorno'],6);
		$giorno_richiesto=$giorno_richiesto<10 && strlen($giorno_richiesto)<2?'0'.$giorno_richiesto:$giorno_richiesto;

		/*
		 * Doppio controllo con checkdate e strtotime per compensare i bug delle singole due funzioni, che
		 * dichiarano valide date illecite.
		 */
		if(! checkdate($mese_richiesto,$giorno_richiesto,$anno_richiesto) || strtotime($anno_richiesto."-".$mese_richiesto."-".$giorno_richiesto)===false){
			echo '<p></p>';
			return;
		}

		//se l'utente corrente non è autorizzato a visualizzare le attività dell'advisor...risposta vuota
		if(! Sicurezza::permettiVisualizzaAttivitaAdvisor($richiesta['cod_advisor'])){
			echo '<p></p>';
			return;			
		}
		$data_richiesta=$anno_richiesto."-".$mese_richiesto."-".$giorno_richiesto;

		$risultati=Doctrine_Core::getTable('Attivita')->getAttivitaPerGiornoPerAdvisor($data_richiesta,$richiesta['cod_advisor']);
		//la data richiesta è in formato mysql: la sostituisco in ogni attività con quella italiana		

		$attivita=array();
		$dateString='%d/%m/%Y';
		//creo un array amichevole per la view
		foreach($risultati as $ris){
			$attivita[]=array(
				$ris['id'],
				anchor('gestioneCliente/index/farma/'.$ris['Farmacia']['id'],$ris['denominazione']),
				$ris['nome'],
				$ris['inizio'],
				mdate($dateString,mysql_to_unix($ris['created_at']))
			);
		}
		$data['permesso_modifiche']=Sicurezza::permettiScriviAttivitaAdvisor($richiesta['cod_advisor']);
		$data['attivita']=$attivita;
		$data['titolo']='Attivita del giorno '.$giorno_richiesto.'/'.$mese_richiesto.'/'.$anno_richiesto;
		$data['intestazione_tabella']=array('Cliente','Attivita','Prevista per','Inserita il');
		$stato=$this->session->userdata('stato');
		$stato['giorno']=str_replace('-','',$data_richiesta);
		$this->session->set_userdata('stato',$stato);
		$this->load->view('tabella_attivita_view-safe',$data);
		
		
		
	}
	
}