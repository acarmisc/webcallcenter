<?php
require_once 'sicurezza.php';
class calendario extends CI_Controller {
	private $data;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date','cookie'));
		$this->load->library(array('session','table','calendar'));
		//se l'utente non risulta loggato, viene reindirizzato sulla home (con form di login)
		Sicurezza::verificaUtente(true);
		//Imposto valori predefiniti
		$this->data['stylesheet']='css/default.css';
		$this->data['mostra_form_login']=false;
		$this->data['login_fallito']=false;
		$this->data['info_utente']=Utente_Corrente::informazioni();
		$this->data['nome_completo']=$this->data['info_utente']['nome_completo'];
		$this->data['data_toolbar']['voci_toolbar']=Sicurezza::vociToolbar();
		

	}
	public function index(){
		//per pura stenografia
		$data=$this->data;
		$data['title']='Pharma3D-Calendario Advisor';
		$data['main_box']='calendario_view';
		$advisors=call_user_func(array($this,Sicurezza::scegliAdvisors()));
		$data['data_mainbox']['advisors']=$advisors;
		//Se vi è un solo advisor disponibile, se ne visualizzano immediatamente i calendari.
		$cod_advisor='';
		if(count($advisors)>1){
			$data['data_mainbox']['visualizza_calendari']=false;
		}else{
			$data['data_mainbox']['visualizza_calendari']=true;
			if(isset($advisors[0])){
				$val=$advisors[0];
			}else{
				$val=$advisors;
			}
			$cod_advisor=array_keys($val);
			$cod_advisor=$cod_advisor[0];
		}

		$stato=$this->gestisciStato($cod_advisor);
		$data['data_mainbox']['mese_corrente']=$stato['mese'];
		$data['data_mainbox']['giorno']=$stato['giorno'];
		$data['data_mainbox']['cod_advisor']=$cod_advisor;
		$this->load->view('template',$data);
	}
	
	private function singoloAdvisor(){
		return array($this->data['info_utente']['cod_advisor']=>$this->data['info_utente']['nome_completo']);
	}
	
	private function advisorsPerAgente(){
		$ag=Doctrine_Core::getTable('Agente')->findById($this->data['info_utente']['cod_agente']);
		$adv=$ag[0]->getAdvisors();
		unset($ag);
		if(count($adv)>1){
			array_unshift($adv,'');
		}
		
		return $adv;
	}
	
	private function elencoAdvisors(){
		$adv=Doctrine_Core::getTable('Advisor')->elencoNomi();
		if(count($adv)>1){
			array_unshift($adv,'');
		}
		return $adv;
	}
	
	
	/*
	 * Gestisce la variabile di sessione 'stato', che restituisce anche come array associativo
	 */
	private function gestisciStato($cod_advisor){
		if(!$this->session->userdata('stato')){
			$adesso=getdate();
			$mese_corrente=$adesso['year'];
			$mese_corrente.=$adesso['mon']<10?'0'.$adesso['mon']:$adesso['mon'];
			$this->session->set_userdata('stato',array('posizione'=>'calendario',
															'mese'=>$mese_corrente,
															'cod_advisor'=>$cod_advisor,
															'giorno'=>''
														)
										);
		}
		return $this->session->userdata('stato');
	}
}