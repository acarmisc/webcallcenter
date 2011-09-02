<?php
require_once 'sicurezza.php';
class TrovaClienti extends CI_Controller{
	private $data;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','cookie'));
		$this->load->library(array('session','table'));
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
		$data['title']='Pharma3D-Ricerca Clienti';
		$data['main_box']='trova_clienti_form';
		$elenchi=call_user_func(array($this,Sicurezza::scegliElenchi()));
		$province=$elenchi['province'];
		$regioni=$elenchi['regioni'];
		$advisors=$elenchi['advisors'];
		$agenti=$elenchi['agenti'];
		$flussi_lavoro=$elenchi['flussi_lavoro'];
		$data['data_mainbox']['province']=$province;
		$data['data_mainbox']['regioni']=$regioni;
		$data['data_mainbox']['advisors']=$advisors;
		$data['data_mainbox']['agenti']=$agenti;
		$data['data_mainbox']['flussi_lavoro']=$flussi_lavoro;
		$data['data_mainbox']['pagina_risultati']='trovaClienti/risultati';
		$this->load->view('template',$data);
	}
	
	public function risultati(){
		//per pura stenografia
		$data=$this->data;
		$data['title']='Pharma3D-Risultati ricerca';
		/*se non si proviene dal form di ricerca...vi si viene reindirizzati
		if(!$this->input->post('submit')){
			redirect('trovaClienti','refresh');
		}*/
		$data['main_box']='risultati_clienti';
		//imposto un array con i criteri scelti dall'utente, che giungono via variabili POST
		$criteri=array();
		if($this->input->post('cod_cliente')){
			$criteri['farmacia_id']=$this->input->post('cod_cliente');
		}
		if($this->input->post('rag_sociale')) {
			$criteri['denominazione']=$this->input->post('rag_sociale') ;
		}
		if($this->input->post('indirizzo')) {
			$criteri['indirizzo']=$this->input->post('indirizzo') ;
		}
		if($this->input->post('cap')) {
			$criteri['cap']= $this->input->post('cap');
		}
		if($this->input->post('localita')) {
			$criteri['localita']=$this->input->post('localita');
		}
		if($this->input->post('telefono')) {
			$criteri['numtel']=$this->input->post('telefono');
		}
		if($this->input->post('fax')) {
			$criteri['numfax']=$this->input->post('fax') ;
		}
		if($this->input->post('email')) {
			$criteri['email']= $this->input->post('email') ;
		}
		if($this->input->post('regione')){
			if($this->input->post('regione')!=0){
				$criteri['regione_id']=$this->input->post('regione');
			}
		}
		if($this->input->post('provincia')){
			if($this->input->post('provincia')!=''){
				$criteri['provincia_id']=$this->input->post('provincia');
			}
		}
		if($this->input->post('advisor')){
			if($this->input->post('advisor')!=''){
				$criteri['advisor_id']=$this->input->post('advisor');
			}
		}
		if($this->input->post('flusso_lavoro')){
			if($this->input->post('flusso_lavoro')!=''){
				$criteri['flusso_lavoro']=$this->input->post('flusso_lavoro');
			}
		}
		if($data['info_utente']['cod_advisor']!=''){
			$criteri['advisor_id']=$data['info_utente']['cod_advisor'];
		}
		if($this->input->post('agente')){
			if($this->input->post('agente')!=''){
				$criteri['agente_id']=$this->input->post('agente');
			}
		}
		if($data['info_utente']['cod_agente']!=''){
			$criteri['agente_id']=$data['info_utente']['cod_agente'];
		}
		if($this->input->post('stato')){
			if($this->input->post('stato')!="0"){
				$criteri['stato']=$this->input->post('stato');
			}
		}
		$risultati=Doctrine_Core::getTable('Farmacia')->ricercaFarmacia($criteri);
		$farmacie=array();
		foreach($risultati as $ris){
			$farmacie[]=array(
				$ris['id'],
				anchor('gestioneCliente/index/farma/'.$ris['id'],$ris['denominazione']),
				$ris['indirizzo'],
				$ris['cap'],
				$ris['localita'],
				$ris['provincia'],
				$ris['regione'],
				$ris['advisor'],
				$ris['agente'],
				$ris['stato']
			);
		}
		$data['data_mainbox']['risultati']=$farmacie;
		$this->load->view('template',$data);

	}
	
	private function elenchiAdvisor(){
		$ad=Doctrine_Core::getTable('Advisor')->findById($this->data['info_utente']['cod_advisor']);
		$ad=$ad[0];
		$elenchi['province']=$ad->getProvince();
		if(count($elenchi['province'])>1) array_unshift($elenchi['province'],'');
		$elenchi['regioni']=$ad->getRegioni();
		if(count($elenchi['regioni'])>1) $elenchi['regioni']=array(''=>'')+$elenchi['regioni'];	
		$elenchi['agenti']=$ad->getAgenti();
		if(count($elenchi['agenti'])>1) array_unshift($elenchi['agenti'],'');
		$elenchi['advisors']=array($ad->id=>$ad->Utente->nome_completo);
		foreach (Doctrine_Core::getTable('Farmacia')->getFlussi_Lavoro() as $flusso){
			$elenchi['flussi_lavoro'][$flusso]=$flusso;			
		}
		if(count($elenchi['flussi_lavoro'])>1) $elenchi['flussi_lavoro']=array(''=>'tutti')+$elenchi['flussi_lavoro'];
		return $elenchi;
	}
	
	private function elenchiAgente(){
		$ag=Doctrine_Core::getTable('Agente')->findById($this->data['info_utente']['cod_agente']);
		$ag=$ag[0];
		$elenchi['province']=$ag->getProvince();
		if(count($elenchi['province'])>1) array_unshift($elenchi['province'],'');
		$elenchi['regioni']=$ag->getRegioni();
		if(count($elenchi['regioni'])>1) $elenchi['regioni']=array(''=>'')+$elenchi['regioni'];	
		$elenchi['agenti']=array($ag->id=>$ag->Utente->nome_completo);
		$elenchi['advisors']=$ag->getAdvisors();
		if(count($elenchi['advisors'])>1) array_unshift($elenchi['advisors'],'');
		foreach (Doctrine_Core::getTable('Farmacia')->getFlussi_Lavoro() as $flusso){
			$elenchi['flussi_lavoro'][$flusso]=$flusso;			
		}		
		if(count($elenchi['flussi_lavoro'])>1) $elenchi['flussi_lavoro']=array(''=>'tutti')+$elenchi['flussi_lavoro'];
		return $elenchi;
	}
	
	private function elenchiCompleti(){
		$elenchi['province']=Doctrine_Core::getTable('Provincia')->elencoRidotto();
		if(count($elenchi['province'])>1) array_unshift($elenchi['province'],'');
		$elenchi['regioni']=Doctrine_Core::getTable('Regione')->elencoRidotto();
		//la riga seguente evita la ricreazione dell'indice effettuata da array_unshift con chiavi numeriche
		$elenchi['regioni']=array(''=>'')+$elenchi['regioni'];
		$elenchi['agenti']=Doctrine_Core::getTable('Agente')->elencoNomi();
		if(count($elenchi['agenti'])>1) array_unshift($elenchi['agenti'],'');
		$elenchi['advisors']=Doctrine_Core::getTable('Advisor')->elencoNomi();
		if(count($elenchi['advisors'])>1) array_unshift($elenchi['advisors'],'');
		foreach (Doctrine_Core::getTable('Farmacia')->getFlussi_Lavoro() as $flusso){
			$elenchi['flussi_lavoro'][$flusso]=$flusso;			
		}
		if(count($elenchi['flussi_lavoro'])>1) $elenchi['flussi_lavoro']=array(''=>'tutti')+$elenchi['flussi_lavoro'];
		return $elenchi;
	}
}